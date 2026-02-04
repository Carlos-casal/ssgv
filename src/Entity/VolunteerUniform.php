<?php

namespace App\Entity;

use App\Repository\VolunteerUniformRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolunteerUniformRepository::class)]
#[ORM\Table(name: 'volunteer_uniform')]
class VolunteerUniform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'uniforms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volunteer $volunteer = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $size = null;

    #[ORM\Column(options: ["default" => 1])]
    private int $quantity = 1;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $assignedAt = null;

    #[ORM\Column(length: 20, options: ["default" => 'active'])]
    private string $status = 'active'; // active, returned, damaged

    public function __construct()
    {
        $this->assignedAt = new \DateTime();
    }

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

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): static
    {
        $this->material = $material;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAssignedAt(): ?\DateTimeInterface
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(\DateTimeInterface $assignedAt): static
    {
        $this->assignedAt = $assignedAt;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
