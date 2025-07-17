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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endTime = null;


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


    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getHours(): ?float
    {
        if ($this->startTime && $this->endTime) {
            $diff = $this->endTime->getTimestamp() - $this->startTime->getTimestamp();
            return $diff / 3600;
        }
        return 0;
    }
}