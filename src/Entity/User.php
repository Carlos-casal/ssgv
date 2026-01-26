<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Ya existe una cuenta con este correo electrónico.')]
/**
 * Represents a user account in the system.
 * This entity is used for authentication and authorization, and is linked to a volunteer profile.
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int|null The unique identifier for the user.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null The user's email address, used as the login identifier.
     */
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var array The roles assigned to the user (e.g., ROLE_ADMIN, ROLE_VOLUNTEER).
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null The hashed password for the user.
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Volunteer|null The volunteer profile associated with this user account.
     */
    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Volunteer $volunteer = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $resetTokenExpiresAt = null;

    /**
     * Gets the unique identifier for the user.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the user's email address.
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the user's email address.
     * @param string $email The new email address.
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     * @return string The user's email address.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Returns the roles granted to the user.
     * @see UserInterface
     * @return array An array of roles.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Sets the roles for the user.
     * @param array $roles The roles to set.
     * @return static
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the hashed password for this user.
     * @see PasswordAuthenticatedUserInterface
     * @return string The hashed password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Sets the hashed password for the user.
     * @param string $password The hashed password.
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;\
    }

    /**
     * Gets the associated volunteer profile.
     * @return Volunteer|null
     */
    public function getVolunteer(): ?Volunteer
    {
        return $this->volunteer;
    }

    /**
     * Sets the associated volunteer profile and maintains the bidirectional relationship.
     * @param Volunteer|null $volunteer The volunteer profile.
     * @return static
     */
    public function setVolunteer(?Volunteer $volunteer): static
    {
        if ($this->volunteer === $volunteer) {
            return $this;
        }

        $this->volunteer = $volunteer;

        if ($volunteer !== null && $volunteer->getUser() !== $this) {
            $volunteer->setUser($this);
        }

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): static
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getResetTokenExpiresAt(): ?\DateTimeImmutable
    {
        return $this->resetTokenExpiresAt;
    }

    public function setResetTokenExpiresAt(?\DateTimeImmutable $resetTokenExpiresAt): static
    {
        $this->resetTokenExpiresAt = $resetTokenExpiresAt;

        return $this;
    }
}