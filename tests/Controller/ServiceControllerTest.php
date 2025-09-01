<?php

namespace App\Tests\Controller;

use App\Entity\AssistanceConfirmation;
use App\Entity\Service;
use App\Entity\User;
use App\Entity\Volunteer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ServiceControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get(EntityManagerInterface::class);
    }

    private function createAdminUser(): User
    {
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setRoles(['ROLE_ADMIN']);
        $passwordHasher = $this->client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    private function createVolunteer(string $email = 'volunteer@test.com'): Volunteer
    {
        $volunteer = new Volunteer();
        $volunteer->setName('Test');
        $volunteer->setLastName('Volunteer');
        $volunteer->setEmail($email);
        $volunteer->setDni(uniqid('DNI_'));
        $this->entityManager->persist($volunteer);
        $this->entityManager->flush();
        return $volunteer;
    }

    private function createService(): Service
    {
        $service = new Service();
        $service->setTitle('Test Service');
        $service->setDate(new \DateTime());
        $service->setDescription('A test service.');
        $this->entityManager->persist($service);
        $this->entityManager->flush();
        return $service;
    }

    private function createAssistanceConfirmation(Service $service, Volunteer $volunteer, bool $attending): AssistanceConfirmation
    {
        $confirmation = new AssistanceConfirmation();
        $confirmation->setService($service);
        $confirmation->setVolunteer($volunteer);
        $confirmation->setHasAttended($attending);
        $this->entityManager->persist($confirmation);
        $this->entityManager->flush();
        return $confirmation;
    }

    public function testAddVolunteersToServiceAsAdmin()
    {
        $adminUser = $this->createAdminUser();
        $this->client->loginUser($adminUser);

        $volunteer1 = $this->createVolunteer('v1@test.com');
        $volunteer2 = $this->createVolunteer('v2@test.com');
        $service = $this->createService();

        $this->client->request(
            'POST',
            '/servicios/' . $service->getId() . '/add-volunteers',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['volunteerIds' => [$volunteer1->getId(), $volunteer2->getId()]])
        );

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $response['status']);
        $this->assertEquals('2 voluntarios añadidos.', $response['message']);

        $confirmations = $this->entityManager->getRepository(AssistanceConfirmation::class)->findBy(['service' => $service]);
        $this->assertCount(2, $confirmations);
    }

    public function testToggleAttendance()
    {
        $adminUser = $this->createAdminUser();
        $this->client->loginUser($adminUser);

        $volunteer = $this->createVolunteer();
        $service = $this->createService();
        $confirmation = $this->createAssistanceConfirmation($service, $volunteer, true);

        $this->assertTrue($confirmation->isHasAttended());

        $this->client->request('POST', '/servicios/' . $service->getId() . '/toggle-attendance/' . $volunteer->getId());

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $response['status']);
        $this->assertFalse($response['newState']);

        $this->entityManager->refresh($confirmation);
        $this->assertFalse($confirmation->isHasAttended());

        // Toggle back
        $this->client->request('POST', '/servicios/' . $service->getId() . '/toggle-attendance/' . $volunteer->getId());
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($response['newState']);

        $this->entityManager->refresh($confirmation);
        $this->assertTrue($confirmation->isHasAttended());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up database after test
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('assistance_confirmation', true));
        $connection->executeStatement($platform->getTruncateTableSQL('service', true));
        $connection->executeStatement($platform->getTruncateTableSQL('volunteer', true));
        $connection->executeStatement($platform->getTruncateTableSQL('user', true));

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
