<?php

namespace App\Security\Voter;

use App\Entity\Service;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class FichajeVoter extends Voter
{
    public const MANAGE_FICHANJE = 'MANAGE_FICHANJE';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::MANAGE_FICHANJE && $subject instanceof Service;
    }

    /**
     * @param string $attribute
     * @param Service $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        // ROLE_ADMIN and ROLE_COORDINATOR can manage any service's fichaje
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_COORDINATOR')) {
            return true;
        }

        $volunteer = $user->getVolunteer();
        if (null === $volunteer) {
            return false;
        }

        // Check if the volunteer is the responsible for fichaje for this service
        foreach ($subject->getAssistanceConfirmations() as $confirmation) {
            if ($confirmation->getVolunteer() === $volunteer && $confirmation->isFichajeResponsible()) {
                return true;
            }
        }

        return false;
    }
}