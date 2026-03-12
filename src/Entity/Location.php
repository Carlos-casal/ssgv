<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    public const TYPE_WAREHOUSE = 'ALMACEN';
    public const TYPE_VEHICLE = 'VEHICULO';
    public const TYPE_KIT = 'KIT';
    public const TYPE_DEPLOYED = 'DESPLEGADO';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Vehicle $vehicle = null;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: MaterialStock::class, orphanRemoval: true)]
    private Collection $stocks;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: MaterialUnit::class)]
    private Collection $units;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: LocationReview::class, orphanRemoval: true)]
    private Collection $reviews;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * @return Collection<int, MaterialStock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(MaterialStock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setLocation($this);
        }

        return $this;
    }

    public function removeStock(MaterialStock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getLocation() === $this) {
                $stock->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MaterialUnit>
     */
    public function getUnits(): Collection
    {
        return $this->units;
    }

    public function addUnit(MaterialUnit $unit): static
    {
        if (!$this->units->contains($unit)) {
            $this->units->add($unit);
            $unit->setLocation($this);
        }

        return $this;
    }

    public function removeUnit(MaterialUnit $unit): static
    {
        if ($this->units->removeElement($unit)) {
            // set the owning side to null (unless already changed)
            if ($unit->getLocation() === $this) {
                $unit->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LocationReview>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(LocationReview $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setLocation($this);
        }

        return $this;
    }

    public function removeReview(LocationReview $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getLocation() === $this) {
                $review->setLocation(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
