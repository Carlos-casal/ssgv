<?php

namespace App\Entity;

use App\Repository\MaterialUnitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterialUnitRepository::class)]
class MaterialUnit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'units')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $serialNumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $collectiveNumber = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastUsedAt = null;

    #[ORM\Column(options: ["default" => false])]
    private bool $isInMaintenance = false;

    // Specific for Walkies
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pttStatus = 'OK';

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $coverStatus = 'OK';

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $batteryStatus = '100%';

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(?string $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getCollectiveNumber(): ?string
    {
        return $this->collectiveNumber;
    }

    public function setCollectiveNumber(?string $collectiveNumber): static
    {
        $this->collectiveNumber = $collectiveNumber;

        return $this;
    }

    public function getLastUsedAt(): ?\DateTimeImmutable
    {
        return $this->lastUsedAt;
    }

    public function setLastUsedAt(?\DateTimeImmutable $lastUsedAt): static
    {
        $this->lastUsedAt = $lastUsedAt;

        return $this;
    }

    public function isInMaintenance(): bool
    {
        return $this->isInMaintenance;
    }

    public function setIsInMaintenance(bool $isInMaintenance): static
    {
        $this->isInMaintenance = $isInMaintenance;

        return $this;
    }

    public function getPttStatus(): ?string
    {
        return $this->pttStatus;
    }

    public function setPttStatus(?string $pttStatus): static
    {
        $this->pttStatus = $pttStatus;

        return $this;
    }

    public function getCoverStatus(): ?string
    {
        return $this->coverStatus;
    }

    public function setCoverStatus(?string $coverStatus): static
    {
        $this->coverStatus = $coverStatus;

        return $this;
    }

    public function getBatteryStatus(): ?string
    {
        return $this->batteryStatus;
    }

    public function setBatteryStatus(?string $batteryStatus): static
    {
        $this->batteryStatus = $batteryStatus;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->material->getName(), $this->serialNumber ?? 'S/N');
    }
}
