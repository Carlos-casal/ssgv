<?php

namespace App\Entity;

use App\Repository\VolunteerServiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types; // Necesario para Types::DATETIME_MUTABLE y Types::FLOAT, Types::TEXT

#[ORM\Entity(repositoryClass: VolunteerServiceRepository::class)]
class VolunteerService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'volunteerServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volunteer $volunteer = null;

    #[ORM\ManyToOne(inversedBy: 'volunteerServices')]
    // Si respondiste 'yes' a la pregunta de nullable, aquí diría 'nullable: true'.
    // Si quieres que no sea nulo, déjalo como 'nullable: false' o elimínalo (por defecto es false).
    // Te recomiendo que sea 'nullable: false' para consistencia.
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $attendedAt = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $hours = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVolunteer(): ?Volunteer
    {
        return $this->volunteer;
    }

    public function setVolunteer(?Volunteer $volunteer): static
    {
        $this->volunteer = $volunteer;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getAttendedAt(): ?\DateTimeInterface
    {
        return $this->attendedAt;
    }

    public function setAttendedAt(\DateTimeInterface $attendedAt): static
    {
        $this->attendedAt = $attendedAt;

        return $this;
    }

    public function getHours(): ?float
    {
        return $this->hours;
    }

    public function setHours(?float $hours): static
    {
        $this->hours = $hours;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}