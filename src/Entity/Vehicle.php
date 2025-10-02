<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $make = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $licensePlate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alias = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    private ?FuelType $fuelType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $nextRevisionDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $insuranceDueDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cabinType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resources = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(string $make): static
    {
        $this->make = $make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(string $licensePlate): static
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?\DateTimeInterface $registrationDate): static
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getFuelType(): ?FuelType
    {
        return $this->fuelType;
    }

    public function setFuelType(?FuelType $fuelType): static
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNextRevisionDate(): ?\DateTimeInterface
    {
        return $this->nextRevisionDate;
    }

    public function setNextRevisionDate(?\DateTimeInterface $nextRevisionDate): static
    {
        $this->nextRevisionDate = $nextRevisionDate;

        return $this;
    }

    public function getInsuranceDueDate(): ?\DateTimeInterface
    {
        return $this->insuranceDueDate;
    }

    public function setInsuranceDueDate(?\DateTimeInterface $insuranceDueDate): static
    {
        $this->insuranceDueDate = $insuranceDueDate;

        return $this;
    }

    public function getCabinType(): ?string
    {
        return $this->cabinType;
    }

    public function setCabinType(?string $cabinType): static
    {
        $this->cabinType = $cabinType;

        return $this;
    }

    public function getResources(): ?string
    {
        return $this->resources;
    }

    public function setResources(?string $resources): static
    {
        $this->resources = $resources;

        return $this;
    }
}