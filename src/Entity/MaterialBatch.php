<?php

namespace App\Entity;

use App\Repository\MaterialBatchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterialBatchRepository::class)]
class MaterialBatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'batches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\Column(length: 100)]
    private ?string $batchNumber = null;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeImmutable $expirationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $supplier = null;

    #[ORM\Column(nullable: true)]
    private ?int $unitsPerPackage = null;

    #[ORM\Column(options: ["default" => 0])]
    private int $numPackages = 0;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, options: ["default" => 0])]
    private string $unitPrice = '0';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $totalPrice = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, options: ["default" => 21])]
    private string $iva = '21';

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $marginPercentage = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'batch', targetEntity: MaterialStock::class, cascade: ['remove'])]
    private Collection $stocks;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->stocks = new ArrayCollection();
    }

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

    public function getBatchNumber(): ?string
    {
        return $this->batchNumber;
    }

    public function setBatchNumber(string $batchNumber): static
    {
        $this->batchNumber = $batchNumber;

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

    public function getUnitsPerPackage(): ?int
    {
        return $this->unitsPerPackage;
    }

    public function setUnitsPerPackage(?int $unitsPerPackage): static
    {
        $this->unitsPerPackage = $unitsPerPackage;

        return $this;
    }

    public function getNumPackages(): int
    {
        return $this->numPackages;
    }

    public function setNumPackages(int $numPackages): static
    {
        $this->numPackages = $numPackages;

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

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

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

    public function getMarginPercentage(): ?string
    {
        return $this->marginPercentage;
    }

    public function setMarginPercentage(?string $marginPercentage): static
    {
        $this->marginPercentage = $marginPercentage;

        return $this;
    }

    public function getValuationPerUnit(): float
    {
        $basePrice = (float)$this->unitPrice;
        $margin = $this->marginPercentage ? (float)$this->marginPercentage : 0.0;
        $ivaRate = (float)$this->iva;

        $priceWithMargin = $basePrice + ($basePrice * $margin / 100);
        return $priceWithMargin * (1 + $ivaRate / 100);
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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
            $stock->setBatch($this);
        }

        return $this;
    }

    public function removeStock(MaterialStock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getBatch() === $this) {
                $stock->setBatch(null);
            }
        }

        return $this;
    }
}
