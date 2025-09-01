<?php

namespace App\Tests\Controller;

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

        // Clean up database before test
        $this->truncateEntities([
            'App\Entity\AssistanceConfirmation',
            'App\Entity\Service',
            'App\Entity\Volunteer',
            'App\Entity\User',
        ]);
    }

    private function truncateEntities(array $entities)
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        foreach ($entities as $entity) {
            $query = $platform->getTruncateTableSQL(
                $this->entityManager->getClassMetadata($entity)->getTableName(), true /* whether to cascade */
            );
            $connection->executeStatement($query);
        }
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

    private function createVolunteer(): Volunteer
    {
        $volunteer = new Volunteer();
        $volunteer->setName('Test');
        $volunteer->setLastName('Volunteer');
        $volunteer->setEmail('volunteer@test.com');
        $volunteer->setDni('12345678Z');
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

    public function testAddVolunteerToServiceAsAdmin()
    {
        $adminUser = $this->createAdminUser();
        $this->client->loginUser($adminUser);

        $volunteer = $this->createVolunteer();
        $service = $this->createService();

        $this->client->request(
            'POST',
            '/servicios/' . $service->getId() . '/add-volunteer',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['volunteerId' => $volunteer->getId()])
        );

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $response['status']);

        // Verify the confirmation was created
        $confirmation = $this->entityManager->getRepository(\App\Entity\AssistanceConfirmation::class)->findOneBy([
            'service' => $service,
            'volunteer' => $volunteer,
        ]);

        $this->assertNotNull($confirmation);
        $this->assertTrue($confirmation->isHasAttended());
    }

    public function testAddVolunteerToServiceAsNonAdmin()
    {
        // Create a non-admin user
        $user = new User();
        $user->setEmail('user@test.com');
        $passwordHasher = $this->client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->client->loginUser($user);

        $volunteer = $this->createVolunteer();
        $service = $this->createService();

        $this->client->request(
            'POST',
            '/servicios/' . $service->getId() . '/add-volunteer',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['volunteerId' => $volunteer->getId()])
        );

        $this->assertResponseStatusCodeSame(403); // Forbidden
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
