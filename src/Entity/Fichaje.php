<?php

namespace App\Entity;

use App\Repository\FichajeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a clock-in/out record for a volunteer's participation in a service.
 * Each `Fichaje` instance marks a single period of time a volunteer was active.
 */
#[ORM\Entity(repositoryClass: FichajeRepository::class)]
class Fichaje
{
    /**
     * @var int|null The unique identifier for the clock-in/out record.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var VolunteerService|null The association between the volunteer and the service for this record.
     */
    #[ORM\ManyToOne(inversedBy: 'fichajes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VolunteerService $volunteerService = null;

    /**
     * @var \DateTimeInterface|null The start time of the clock-in.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startTime = null;

    /**
     * @var \DateTimeInterface|null The end time of the clock-out. Can be null if the volunteer is currently clocked in.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endTime = null;

    /**
     * @var string|null Optional notes for this specific clock-in/out period.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    /**
     * Gets the unique identifier for the clock-in/out record.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the associated volunteer-service link.
     * @return VolunteerService|null
     */
    public function getVolunteerService(): ?VolunteerService
    {
        return $this->volunteerService;
    }

    /**
     * Sets the associated volunteer-service link.
     * @param VolunteerService|null $volunteerService The volunteer-service association.
     * @return static
     */
    public function setVolunteerService(?VolunteerService $volunteerService): static
    {
        $this->volunteerService = $volunteerService;

        return $this;
    }

    /**
     * Gets the start time of the clock-in.
     * @return \DateTimeInterface|null
     */
    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    /**
     * Sets the start time of the clock-in.
     * @param \DateTimeInterface $startTime The start time.
     * @return static
     */
    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Gets the end time of the clock-out.
     * @return \DateTimeInterface|null
     */
    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    /**
     * Sets the end time of the clock-out.
     * @param \DateTimeInterface|null $endTime The end time.
     * @return static
     */
    public function setEndTime(?\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Gets the notes for this record.
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * Sets the notes for this record.
     * @param string|null $notes The notes.
     * @return static
     */
    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
