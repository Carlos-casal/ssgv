<?php

namespace App\Entity;

use App\Repository\FuelTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a type of fuel that a vehicle can use (e.g., Diesel, Gasoline).
 */
#[ORM\Entity(repositoryClass: FuelTypeRepository::class)]
class FuelType
{
    /**
     * @var int|null The unique identifier for the fuel type.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null The name of the fuel type (e.g., "Diesel").
     */
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, Vehicle> A collection of vehicles that use this fuel type.
     */
    #[ORM\OneToMany(mappedBy: 'fuelType', targetEntity: Vehicle::class)]
    private Collection $vehicles;

    /**
     * Initializes the vehicles collection.
     */
    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
    }

    /**
     * Gets the unique identifier for the fuel type.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the name of the fuel type.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the fuel type.
     * @param string $name The new name.
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the collection of vehicles that use this fuel type.
     * @return Collection<int, Vehicle>
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    /**
     * Adds a vehicle to the collection of vehicles that use this fuel type.
     * @param Vehicle $vehicle The vehicle to add.
     * @return static
     */
    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setFuelType($this);
        }

        return $this;
    }

    /**
     * Removes a vehicle from the collection of vehicles that use this fuel type.
     * @param Vehicle $vehicle The vehicle to remove.
     * @return static
     */
    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            // set the owning side to null (unless already changed)
            if ($vehicle->getFuelType() === $this) {
                $vehicle->setFuelType(null);
            }
        }

        return $this;
    }

    /**
     * Returns the string representation of the fuel type, which is its name.
     * @return string
     */
    public function __toString(): string
    {
        return $this->name ?? '';
    }
}