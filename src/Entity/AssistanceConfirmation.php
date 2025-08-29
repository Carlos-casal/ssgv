<?php

namespace App\Entity;

use App\Repository\AssistanceConfirmationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssistanceConfirmationRepository::class)]
class AssistanceConfirmation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $hasAttended = null;

    #[ORM\ManyToOne(inversedBy: 'assistanceConfirmations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    #[ORM\ManyToOne(inversedBy: 'assistanceConfirmations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volunteer $volunteer = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $checkInTime = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $checkOutTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isHasAttended(): ?bool
    {
        return $this->hasAttended;
    }

    public function setHasAttended(bool $hasAttended): static
    {
        $this->hasAttended = $hasAttended;

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

    public function getVolunteer(): ?Volunteer
    {
        return $this->volunteer;
    }

    public function setVolunteer(?Volunteer $volunteer): static
    {
        $this->volunteer = $volunteer;

        return $this;
    }

    public function getCheckInTime(): ?\DateTimeInterface
    {
        return $this->checkInTime;
    }

    public function setCheckInTime(?\DateTimeInterface $checkInTime): static
    {
        $this->checkInTime = $checkInTime;

        return $this;
    }

    public function getCheckOutTime(): ?\DateTimeInterface
    {
        return $this->checkOutTime;
    }

    public function setCheckOutTime(?\DateTimeInterface $checkOutTime): static
    {
        $this->checkOutTime = $checkOutTime;

        return $this;
    }
}