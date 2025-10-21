<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a vehicle in the organization's fleet.
 * Contains details about the vehicle, such as its make, model, license plate, and maintenance information.
 */
#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    /**
     * @var int|null The unique identifier for the vehicle.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null The manufacturer of the vehicle (e.g., "Ford").
     */
    #[ORM\Column(length: 255)]
    private ?string $make = null;

    /**
     * @var string|null The model of the vehicle (e.g., "Transit").
     */
    #[ORM\Column(length: 255)]
    private ?string $model = null;

    /**
     * @var string|null The license plate number of the vehicle. Must be unique.
     */
    #[ORM\Column(length: 255, unique: true)]
    private ?string $licensePlate = null;

    /**
     * @var string|null The filename of the vehicle's photo.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    /**
     * @var string|null An alias or internal name for the vehicle.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alias = null;

    /**
     * @var \DateTimeInterface|null The date the vehicle was first registered.
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationDate = null;

    /**
     * @var FuelType|null The type of fuel the vehicle uses.
     */
    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    private ?FuelType $fuelType = null;

    /**
     * @var string|null The type of vehicle (e.g., "Ambulance", "Car").
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    /**
     * @var \DateTimeInterface|null The date of the next scheduled technical revision.
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $nextRevisionDate = null;

    /**
     * @var \DateTimeInterface|null The due date for the vehicle's insurance.
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $insuranceDueDate = null;

    /**
     * @var string|null The type of cabin (e.g., "Simple", "Doble").
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cabinType = null;

    /**
     * @var string|null A description of the resources or equipment available in the vehicle.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resources = null;

    /**
     * @var bool Indicates whether the vehicle is currently out of service.
     */
    #[ORM\Column(type: 'boolean')]
    private bool $isOutOfService = false;

    /**
     * @var Collection<int, ServiceVehicle>
     */
    #[ORM\OneToMany(mappedBy: 'vehicle', targetEntity: ServiceVehicle::class, orphanRemoval: true)]
    private Collection $serviceVehicles;

    public function __construct()
    {
        $this->serviceVehicles = new ArrayCollection();
    }

    /**
     * Gets the unique identifier for the vehicle.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the manufacturer of the vehicle.
     * @return string|null
     */
    public function getMake(): ?string
    {
        return $this->make;
    }

    /**
     * Sets the manufacturer of the vehicle.
     * @param string $make The manufacturer.
     * @return static
     */
    public function setMake(string $make): static
    {
        $this->make = $make;

        return $this;
    }

    /**
     * Gets the model of the vehicle.
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * Sets the model of the vehicle.
     * @param string $model The model.
     * @return static
     */
    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Gets the license plate number.
     * @return string|null
     */
    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    /**
     * Sets the license plate number.
     * @param string $licensePlate The license plate number.
     * @return static
     */
    public function setLicensePlate(string $licensePlate): static
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    /**
     * Gets the filename of the vehicle's photo.
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * Sets the filename of the vehicle's photo.
     * @param string|null $photo The photo filename.
     * @return static
     */
    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Gets the alias of the vehicle.
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * Sets the alias of the vehicle.
     * @param string|null $alias The alias.
     * @return static
     */
    public function setAlias(?string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Gets the registration date of the vehicle.
     * @return \DateTimeInterface|null
     */
    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    /**
     * Sets the registration date of the vehicle.
     * @param \DateTimeInterface|null $registrationDate The registration date.
     * @return static
     */
    public function setRegistrationDate(?\DateTimeInterface $registrationDate): static
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Gets the fuel type of the vehicle.
     * @return FuelType|null
     */
    public function getFuelType(): ?FuelType
    {
        return $this->fuelType;
    }

    /**
     * Sets the fuel type of the vehicle.
     * @param FuelType|null $fuelType The fuel type.
     * @return static
     */
    public function setFuelType(?FuelType $fuelType): static
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * Gets the type of the vehicle.
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the type of the vehicle.
     * @param string|null $type The vehicle type.
     * @return static
     */
    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the next revision date.
     * @return \DateTimeInterface|null
     */
    public function getNextRevisionDate(): ?\DateTimeInterface
    {
        return $this->nextRevisionDate;
    }

    /**
     * Sets the next revision date.
     * @param \DateTimeInterface|null $nextRevisionDate The next revision date.
     * @return static
     */
    public function setNextRevisionDate(?\DateTimeInterface $nextRevisionDate): static
    {
        $this->nextRevisionDate = $nextRevisionDate;

        return $this;
    }

    /**
     * Gets the insurance due date.
     * @return \DateTimeInterface|null
     */
    public function getInsuranceDueDate(): ?\DateTimeInterface
    {
        return $this->insuranceDueDate;
    }

    /**
     * Sets the insurance due date.
     * @param \DateTimeInterface|null $insuranceDueDate The insurance due date.
     * @return static
     */
    public function setInsuranceDueDate(?\DateTimeInterface $insuranceDueDate): static
    {
        $this->insuranceDueDate = $insuranceDueDate;

        return $this;
    }

    /**
     * Gets the cabin type.
     * @return string|null
     */
    public function getCabinType(): ?string
    {
        return $this->cabinType;
    }

    /**
     * Sets the cabin type.
     * @param string|null $cabinType The cabin type.
     * @return static
     */
    public function setCabinType(?string $cabinType): static
    {
        $this->cabinType = $cabinType;

        return $this;
    }

    /**
     * Gets the resources available in the vehicle.
     * @return string|null
     */
    public function getResources(): ?string
    {
        return $this->resources;
    }

    /**
     * Sets the resources available in the vehicle.
     * @param string|null $resources The resources.
     * @return static
     */
    public function setResources(?string $resources): static
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * Checks if the vehicle is out of service.
     * @return bool
     */
    public function isOutOfService(): bool
    {
        return $this->isOutOfService;
    }

    /**
     * Sets the out-of-service status of the vehicle.
     * @param bool $isOutOfService True if the vehicle is out of service.
     * @return static
     */
    public function setOutOfService(bool $isOutOfService): static
    {
        $this->isOutOfService = $isOutOfService;

        return $this;
    }

    /**
     * @return Collection<int, ServiceVehicle>
     */
    public function getServiceVehicles(): Collection
    {
        return $this->serviceVehicles;
    }

    public function addServiceVehicle(ServiceVehicle $serviceVehicle): static
    {
        if (!$this->serviceVehicles->contains($serviceVehicle)) {
            $this->serviceVehicles->add($serviceVehicle);
            $serviceVehicle->setVehicle($this);
        }

        return $this;
    }

    public function removeServiceVehicle(ServiceVehicle $serviceVehicle): static
    {
        if ($this->serviceVehicles->removeElement($serviceVehicle)) {
            // set the owning side to null (unless already changed)
            if ($serviceVehicle->getVehicle() === $this) {
                $serviceVehicle->setVehicle(null);
            }
        }

        return $this;
    }
}