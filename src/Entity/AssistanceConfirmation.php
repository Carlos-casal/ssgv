<?php

namespace App\Entity;

use App\Repository\AssistanceConfirmationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Represents the confirmation of a volunteer's assistance for a specific service.
 * It tracks whether a volunteer is attending, not attending, or on the reserve list.
 */
#[ORM\Entity(repositoryClass: AssistanceConfirmationRepository::class)]
class AssistanceConfirmation
{
    /** @var string Status indicating the volunteer will attend the service. */
    public const STATUS_ATTENDING = 'attending';
    /** @var string Status indicating the volunteer will not attend the service. */
    public const STATUS_NOT_ATTENDING = 'not_attending';
    /** @var string Status indicating the volunteer is on the reserve list for the service. */
    public const STATUS_RESERVED = 'reserved';

    /**
     * @var int|null The unique identifier for the assistance confirmation.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null The status of the assistance (e.g., attending, not attending, reserved).
     */
    #[ORM\Column(length: 255, options: ['default' => self::STATUS_NOT_ATTENDING])]
    private ?string $status = self::STATUS_NOT_ATTENDING;

    /**
     * @var Service|null The service associated with this confirmation.
     */
    #[ORM\ManyToOne(inversedBy: 'assistanceConfirmations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    /**
     * @var Volunteer|null The volunteer associated with this confirmation.
     */
    #[ORM\ManyToOne(inversedBy: 'assistanceConfirmations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volunteer $volunteer = null;

    /**
     * @var \DateTimeInterface|null The timestamp when the confirmation was created.
     */
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    /**
     * @var \DateTimeInterface|null The timestamp when the confirmation was last updated.
     */
    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    /**
     * @var bool Indicates if the volunteer is responsible for clock-in/out records for this service.
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isFichajeResponsible = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $justification = null;

    #[ORM\ManyToOne(targetEntity: Vehicle::class)]
    private ?Vehicle $assignedVehicle = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $vehicleRole = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isSanitario = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isSocorrista = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isConductor = false;

    /**
     * Gets the unique identifier for the assistance confirmation.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the status of the assistance.
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Sets the status of the assistance.
     * @param string $status The new status. Must be one of the STATUS_* constants.
     * @return static
     * @throws \InvalidArgumentException If the status is invalid.
     */
    public function setStatus(string $status): static
    {
        if (!in_array($status, [self::STATUS_ATTENDING, self::STATUS_NOT_ATTENDING, self::STATUS_RESERVED])) {
            throw new \InvalidArgumentException("Invalid status");
        }
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the service associated with this confirmation.
     * @return Service|null
     */
    public function getService(): ?Service
    {
        return $this->service;
    }

    /**
     * Sets the service associated with this confirmation.
     * @param Service|null $service The service.
     * @return static
     */
    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Gets the volunteer associated with this confirmation.
     * @return Volunteer|null
     */
    public function getVolunteer(): ?Volunteer
    {
        return $this->volunteer;
    }

    /**
     * Sets the volunteer associated with this confirmation.
     * @param Volunteer|null $volunteer The volunteer.
     * @return static
     */
    public function setVolunteer(?Volunteer $volunteer): static
    {
        $this->volunteer = $volunteer;

        return $this;
    }

    /**
     * Gets the creation timestamp.
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets the last update timestamp.
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Checks if the volunteer is responsible for clock-in/out records.
     * @return bool
     */
    public function isFichajeResponsible(): bool
    {
        return $this->isFichajeResponsible;
    }

    /**
     * Sets whether the volunteer is responsible for clock-in/out records.
     * @param bool $isFichajeResponsible True if the volunteer is responsible, false otherwise.
     * @return static
     */
    public function setFichajeResponsible(bool $isFichajeResponsible): static
    {
        $this->isFichajeResponsible = $isFichajeResponsible;

        return $this;
    }

    /**
     * Checks if the volunteer's status is 'attending'.
     * @return bool True if the status is 'attending', false otherwise.
     */
    public function hasAttended(): bool
    {
        return $this->status === self::STATUS_ATTENDING;
    }

    public function isSanitario(): bool
    {
        return $this->isSanitario;
    }

    public function setSanitario(bool $isSanitario): static
    {
        $this->isSanitario = $isSanitario;

        return $this;
    }

    public function isSocorrista(): bool
    {
        return $this->isSocorrista;
    }

    public function setSocorrista(bool $isSocorrista): static
    {
        $this->isSocorrista = $isSocorrista;

        return $this;
    }

    public function isConductor(): bool
    {
        return $this->isConductor;
    }

    public function setConductor(bool $isConductor): static
    {
        $this->isConductor = $isConductor;

        return $this;
    }

    public function getJustification(): ?string
    {
        return $this->justification;
    }

    public function setJustification(?string $justification): self
    {
        $this->justification = $justification;
        return $this;
    }

    public function getAssignedVehicle(): ?Vehicle
    {
        return $this->assignedVehicle;
    }

    public function setAssignedVehicle(?Vehicle $assignedVehicle): self
    {
        $this->assignedVehicle = $assignedVehicle;
        return $this;
    }

    public function getVehicleRole(): ?string
    {
        return $this->vehicleRole;
    }

    public function setVehicleRole(?string $vehicleRole): self
    {
        $this->vehicleRole = $vehicleRole;
        return $this;
    }
}