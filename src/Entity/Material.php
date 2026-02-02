<?php

namespace App\Entity;

use App\Repository\MaterialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterialRepository::class)]
#[ORM\Table(name: 'maestro_material')]
class Material
{
    public const NATURE_CONSUMABLE = 'CONSUMIBLE';
    public const NATURE_TECHNICAL = 'EQUIPO_TECNICO';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $category = null; // e.g. 'Sanitario', 'Comunicaciones', 'Logística'

    #[ORM\Column(length: 20, options: ["default" => self::NATURE_CONSUMABLE])]
    private string $nature = self::NATURE_CONSUMABLE;

    #[ORM\Column(options: ["default" => 0])]
    private int $stock = 0;

    #[ORM\Column(name: "safety_stock", options: ["default" => 0])]
    private int $safetyStock = 0;

    #[ORM\OneToMany(mappedBy: 'material', targetEntity: ServiceMaterial::class, orphanRemoval: true)]
    private Collection $serviceMaterials;

    #[ORM\OneToMany(mappedBy: 'material', targetEntity: MaterialUnit::class, orphanRemoval: true)]
    private Collection $units;

    public function __construct()
    {
        $this->serviceMaterials = new ArrayCollection();
        $this->units = new ArrayCollection();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getNature(): string
    {
        return $this->nature;
    }

    public function setNature(string $nature): static
    {
        $this->nature = $nature;

        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSafetyStock(): int
    {
        return $this->safetyStock;
    }

    public function setSafetyStock(int $safetyStock): static
    {
        $this->safetyStock = $safetyStock;

        return $this;
    }

    /**
     * @return Collection<int, ServiceMaterial>
     */
    public function getServiceMaterials(): Collection
    {
        return $this->serviceMaterials;
    }

    public function addServiceMaterial(ServiceMaterial $serviceMaterial): static
    {
        if (!$this->serviceMaterials->contains($serviceMaterial)) {
            $this->serviceMaterials->add($serviceMaterial);
            $serviceMaterial->setMaterial($this);
        }

        return $this;
    }

    public function removeServiceMaterial(ServiceMaterial $serviceMaterial): static
    {
        if ($this->serviceMaterials->removeElement($serviceMaterial)) {
            if ($serviceMaterial->getMaterial() === $this) {
                $serviceMaterial->setMaterial(null);
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
            $unit->setMaterial($this);
        }

        return $this;
    }

    public function removeUnit(MaterialUnit $unit): static
    {
        if ($this->units->removeElement($unit)) {
            if ($unit->getMaterial() === $this) {
                $unit->setMaterial(null);
            }
        }

        return $this;
    }

    public function isLowStock(): bool
    {
        return $this->nature === self::NATURE_CONSUMABLE && $this->stock <= $this->safetyStock;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
