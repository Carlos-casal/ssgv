<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\DniNie;

/**
 * Represents a volunteer's profile, containing personal information, qualifications, and status.
 * This entity is linked to a User account for authentication.
 */
#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
#[UniqueEntity(fields: ['indicativo'], message: 'Este indicativo ya está en uso.')]
class Volunteer
{
    /** @var string Status for an active volunteer. */
    public const STATUS_ACTIVE = 'Activo';
    /** @var string Status for a suspended volunteer. */
    public const STATUS_SUSPENDED = 'Suspensión';
    /** @var string Status for an inactive (resigned) volunteer. */
    public const STATUS_INACTIVE = 'Baja';
    /** @var string Status for a new registration pending approval. */
    public const STATUS_PENDING = 'pending';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El nombre no puede estar vacío.')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Los apellidos no pueden estar vacíos.')]
    private ?string $lastName = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'El teléfono no puede estar vacío.')]
    #[Assert\Regex(
        pattern: '/^(\+34)?[6789]\d{8}$/',
        message: 'El número de teléfono no parece un formato español válido.'
    )]
    private ?string $phone = null;

    #[ORM\Column(length: 15, unique: true)]
    #[Assert\NotBlank(message: 'El DNI/NIE no puede estar vacío.')]
    #[DniNie]
    private ?string $dni = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La fecha de nacimiento no puede estar vacía.')]
    #[Assert\LessThan(
        value: '-16 years',
        message: 'El voluntario debe tener al menos 16 años cumplidos.'
    )]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'El tipo de vía no puede estar vacío.')]
    private ?string $streetType = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La dirección no puede estar vacía.')]
    private ?string $address = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: 'El código postal no puede estar vacío.')]
    private ?string $postalCode = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'La provincia no puede estar vacía.')]
    private ?string $province = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'La población no puede estar vacía.')]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El nombre del contacto de emergencia no puede estar vacío.')]
    private ?string $contactPerson1 = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'El teléfono del contacto de emergencia no puede estar vacío.')]
    private ?string $contactPhone1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPerson2 = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $contactPhone2 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $foodAllergies = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $otherAllergies = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $profession = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $employmentStatus = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $drivingLicenses = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $drivingLicenseExpiryDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $languages = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'El motivo no puede estar vacío.')]
    private ?string $motivation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Este campo no puede estar vacío.')]
    private ?string $howKnown = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $hasVolunteeredBefore = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $previousVolunteeringInstitutions = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $otherQualifications = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $navigationLicenses = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $specificQualifications = null;

    #[ORM\Column(length: 100)]
    private ?string $role = null;

    #[ORM\Column(length: 20)]
    private ?string $status = self::STATUS_ACTIVE;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $joinDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $statusChangeDate = null;

    #[ORM\OneToOne(inversedBy: 'volunteer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $indicativo = null;

    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: AssistanceConfirmation::class)]
    private Collection $assistanceConfirmations;

    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: VolunteerService::class)]
    private Collection $volunteerServices;

    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: VolunteerUniform::class, orphanRemoval: true)]
    private Collection $uniforms;

    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: UniformMovement::class)]
    private Collection $uniformMovements;

    public function __construct()
    {
        $this->assistanceConfirmations = new ArrayCollection();
        $this->volunteerServices = new ArrayCollection();
        $this->uniforms = new ArrayCollection();
        $this->uniformMovements = new ArrayCollection();
    }

    // ... (all existing getter/setter methods remain the same)

    public function getVolunteerServiceForService(Service $service): ?VolunteerService
    {
        foreach ($this->volunteerServices as $vs) {
            if ($vs->getService() === $service) {
                return $vs;
            }
        }
        return null;
    }

    /**
     * @return Collection<int, VolunteerUniform>
     */
    public function getUniforms(): Collection
    {
        return $this->uniforms;
    }

    public function addUniform(VolunteerUniform $uniform): static
    {
        if (!$this->uniforms->contains($uniform)) {
            $this->uniforms->add($uniform);
            $uniform->setVolunteer($this);
        }

        return $this;
    }

    public function removeUniform(VolunteerUniform $uniform): static
    {
        if ($this->uniforms->removeElement($uniform)) {
            if ($uniform->getVolunteer() === $this) {
                $uniform->setVolunteer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UniformMovement>
     */
    public function getUniformMovements(): Collection
    {
        return $this->uniformMovements;
    }

    public function addUniformMovement(UniformMovement $uniformMovement): static
    {
        if (!$this->uniformMovements->contains($uniformMovement)) {
            $this->uniformMovements->add($uniformMovement);
            $uniformMovement->setVolunteer($this);
        }

        return $this;
    }

    public function removeUniformMovement(UniformMovement $uniformMovement): static
    {
        if ($this->uniformMovements->removeElement($uniformMovement)) {
            if ($uniformMovement->getVolunteer() === $this) {
                $uniformMovement->setVolunteer(null);
            }
        }

        return $this;
    }

    // NOTE: This file has been truncated for brevity. All existing getter/setter methods
    // from the original file should remain between the constructor and getVolunteerServiceForService
}
