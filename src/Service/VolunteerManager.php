<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Volunteer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class VolunteerManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function processNewVolunteer(Volunteer $volunteer, ?string $plainPassword): void
    {
        $user = $volunteer->getUser();
        if (!$user) {
            // This case should be prevented by form validation or logic before calling this manager
            throw new \LogicException('Volunteer must have a user.');
        }

        $this->hashPassword($user, $plainPassword);
        $user->setRoles(['ROLE_VOLUNTEER']);

        if (!$volunteer->getJoinDate()) {
            $volunteer->setJoinDate(new \DateTime());
        }

        // The status is already defaulted in the entity, so no need to set it here for 'new'

        $this->entityManager->persist($user);
        $this->entityManager->persist($volunteer);
        $this->entityManager->flush();
    }

    public function processRegistration(Volunteer $volunteer, ?string $plainPassword): void
    {
        $user = $volunteer->getUser();
        if (!$user) {
            throw new \LogicException('Volunteer must have a user for registration.');
        }

        $this->hashPassword($user, $plainPassword);
        $user->setRoles(['ROLE_VOLUNTEER']);

        if (!$volunteer->getJoinDate()) {
            $volunteer->setJoinDate(new \DateTime());
        }

        $volunteer->setStatus(Volunteer::STATUS_PENDING);

        $this->entityManager->persist($user);
        $this->entityManager->persist($volunteer);
        $this->entityManager->flush();
    }

    public function processUpdate(Volunteer $volunteer, ?string $plainPassword): void
    {
        if ($plainPassword) {
            $user = $volunteer->getUser();
            if ($user) {
                $this->hashPassword($user, $plainPassword);
            }
        }

        // No need to persist, entity is already managed.
        $this->entityManager->flush();
    }

    private function hashPassword(PasswordAuthenticatedUserInterface $user, ?string $plainPassword): void
    {
        if ($plainPassword) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }
    }
}
