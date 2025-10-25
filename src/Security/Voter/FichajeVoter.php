<?php

namespace App\Security\Voter;

use App\Entity\Service;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * A Symfony Voter to determine if a user has permission to manage clock-in/out records (fichajes) for a given service.
 */
class FichajeVoter extends Voter
{
    /** @var string The permission to manage fichajes. */
    public const MANAGE_FICHANJE = 'MANAGE_FICHANJE';

    private Security $security;

    /**
     * FichajeVoter constructor.
     * @param Security $security The security component to check for roles.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if this voter supports the given attribute and subject.
     *
     * @param string $attribute The attribute to check (e.g., 'MANAGE_FICHANJE').
     * @param mixed $subject The subject to check (must be an instance of Service).
     * @return bool True if this voter supports the attribute and subject, false otherwise.
     */
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::MANAGE_FICHANJE && $subject instanceof Service;
    }

    /**
     * Performs the main authorization logic.
     *
     * A user can manage fichajes if:
     * - They have the ROLE_ADMIN or ROLE_COORDINATOR.
     * - They are a volunteer who has been designated as the responsible person for fichajes for that specific service.
     *
     * @param string $attribute The attribute being voted on.
     * @param Service $subject The service entity.
     * @param TokenInterface $token The user's security token.
     * @return bool True to grant access, false otherwise.
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