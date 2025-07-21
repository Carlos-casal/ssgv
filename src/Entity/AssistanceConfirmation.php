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

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $checkIn = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $checkOut = null;

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

    public function getCheckIn(): ?\DateTimeInterface
    {
        return $this->checkIn;
    }

    public function setCheckIn(?\DateTimeInterface $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?\DateTimeInterface
    {
        return $this->checkOut;
    }

    public function setCheckOut(?\DateTimeInterface $checkOut): static
    {
        $this->checkOut = $checkOut;

        return $this;
    }
}