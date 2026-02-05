<?php

namespace App\Entity;

use App\Repository\MaterialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterialRepository::class)]
#[ORM\Table(name: 'maestro_material')]
#[ORM\HasLifecycleCallbacks]
class Material
{
    public const NATURE_CONSUMABLE = 'CONSUMIBLE';
    public const NATURE_TECHNICAL = 'EQUIPO_TECNICO';

    public const SIZING_LETTER = 'LETTER';
    public const SIZING_NUMBER_CLOTHING = 'NUMBER_CLOTHING';
    public const SIZING_NUMBER_SHOES = 'NUMBER_SHOES';

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

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $sizingType = null;

    #[ORM\Column(options: ["default" => 0])]
    private int $stock = 0;

    #[ORM\Column(name: "safety_stock", options: ["default" => 0])]
    private int $safetyStock = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $barcode = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $batch = null;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeImmutable $expirationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $supplier = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, options: ["default" => 0])]
    private string $unitPrice = '0';

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, options: ["default" => 21])]
    private string $iva = '21';

    #[ORM\OneToMany(mappedBy: 'material', targetEntity: ServiceMaterial::class, orphanRemoval: true)]
    private Collection $serviceMaterials;

    #[ORM\OneToMany(mappedBy: 'material', targetEntity: MaterialUnit::class, orphanRemoval: true)]
    private Collection $units;

    #[ORM\OneToMany(mappedBy: 'material', targetEntity: MaterialStock::class, orphanRemoval: true)]
    private Collection $stocks;

    public function __construct()
    {
        $this->serviceMaterials = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->stocks = new ArrayCollection();
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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function syncNatureWithCategory(): void
    {
        $technicalCategories = ['Comunicaciones', 'Vehículos', 'Mar', 'Logística'];
        if (in_array($this->category, $technicalCategories)) {
            $this->nature = self::NATURE_TECHNICAL;
        }
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

    public function getSizingType(): ?string
    {
        return $this->sizingType;
    }

    public function setSizingType(?string $sizingType): static
    {
        $this->sizingType = $sizingType;

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
            $stock->setMaterial($this);
        }

        return $this;
    }

    public function removeStock(MaterialStock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            if ($stock->getMaterial() === $this) {
                $stock->setMaterial(null);
            }
        }

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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): static
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBatch(): ?string
    {
        return $this->batch;
    }

    public function setBatch(?string $batch): static
    {
        $this->batch = $batch;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?\DateTimeImmutable $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getSupplier(): ?string
    {
        return $this->supplier;
    }

    public function setSupplier(?string $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getUnitPrice(): string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(string $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getIva(): string
    {
        return $this->iva;
    }

    public function setIva(string $iva): static
    {
        $this->iva = $iva;

        return $this;
    }

    public function getExpirationStatus(): string
    {
        if ($this->expirationDate === null) {
            return 'gray';
        }

        $now = new \DateTimeImmutable('today');
        $sixMonths = $now->modify('+6 months');

        if ($this->expirationDate <= $now) {
            return 'red';
        }

        if ($this->expirationDate <= $sixMonths) {
            return 'yellow';
        }

        return 'green';
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
