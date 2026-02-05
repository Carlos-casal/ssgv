<?php

namespace App\Entity;

use App\Repository\LocationReviewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationReviewRepository::class)]
class LocationReview
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $reviewDate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volunteer $responsible = null;

    #[ORM\Column(length: 255)]
    private ?string $result = null; // 'CONFORME' or 'FALTAN_ARTICULOS'

    #[ORM\Column]
    private ?\DateTimeImmutable $nextReviewDate = null;

    public function __construct()
    {
        $this->reviewDate = new \DateTimeImmutable();
        $this->nextReviewDate = $this->reviewDate->modify('+30 days');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getReviewDate(): ?\DateTimeImmutable
    {
        return $this->reviewDate;
    }

    public function setReviewDate(\DateTimeImmutable $reviewDate): static
    {
        $this->reviewDate = $reviewDate;

        return $this;
    }

    public function getResponsible(): ?Volunteer
    {
        return $this->responsible;
    }

    public function setResponsible(?Volunteer $responsible): static
    {
        $this->responsible = $responsible;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getNextReviewDate(): ?\DateTimeImmutable
    {
        return $this->nextReviewDate;
    }

    public function setNextReviewDate(\DateTimeImmutable $nextReviewDate): static
    {
        $this->nextReviewDate = $nextReviewDate;

        return $this;
    }
}
