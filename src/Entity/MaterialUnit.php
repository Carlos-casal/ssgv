<?php

namespace App\Entity;

use App\Repository\MaterialUnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MaterialUnitRepository::class)]
#[UniqueEntity(fields: ['alias'], message: 'Este Alias ya está en uso por otra unidad.', ignoreNull: true)]
class MaterialUnit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'units')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\ManyToOne(inversedBy: 'units')]
    private ?Location $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $serialNumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $collectiveNumber = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $lastUsedAt = null;

    #[ORM\Column(options: ["default" => false])]
    private bool $isInMaintenance = false;

    // Specific for Walkies
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pttStatus = 'OK';

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $coverStatus = 'OK';

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $batteryStatus = '100%';

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $alias = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $networkId = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $operationalStatus = 'OPERATIVO';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $purchasePrice = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $discountPct = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $supplier = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, options: ["default" => 21])]
    private string $iva = '21';

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $marginPercentage = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $purchaseDate = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $warrantyDate = null;

    #[ORM\Column(options: ["default" => false])]
    private bool $hasCharger = false;

    #[ORM\Column(options: ["default" => false])]
    private bool $hasClip = false;

    #[ORM\Column(options: ["default" => false])]
    private bool $hasMicrophone = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brandModel = null;

    #[ORM\ManyToOne]
    private ?KitTemplate $template = null;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'materialUnits')]
    private ?Location $kitLocation = null;

    #[ORM\OneToMany(mappedBy: 'materialUnit', targetEntity: MaterialUnitHistory::class, orphanRemoval: true)]
    private Collection $history;

    public function __construct()
    {
        $this->history = new ArrayCollection();
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

    public function getPurchaseDate(): ?\DateTime
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(?\DateTime $purchaseDate): static
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getWarrantyDate(): ?\DateTime
    {
        return $this->warrantyDate;
    }

    public function setWarrantyDate(?\DateTime $warrantyDate): static
    {
        $this->warrantyDate = $warrantyDate;

        return $this;
    }

    public function hasCharger(): bool
    {
        return $this->hasCharger;
    }

    public function setHasCharger(bool $hasCharger): static
    {
        $this->hasCharger = $hasCharger;

        return $this;
    }

    public function hasClip(): bool
    {
        return $this->hasClip;
    }

    public function setHasClip(bool $hasClip): static
    {
        $this->hasClip = $hasClip;

        return $this;
    }

    public function hasMicrophone(): bool
    {
        return $this->hasMicrophone;
    }

    public function setHasMicrophone(bool $hasMicrophone): static
    {
        $this->hasMicrophone = $hasMicrophone;

        return $this;
    }

    public function getBrandModel(): ?string
    {
        return $this->brandModel;
    }

    public function setBrandModel(?string $brandModel): static
    {
        $this->brandModel = $brandModel;

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

    public function getLastUsedAt(): ?\DateTime
    {
        return $this->lastUsedAt;
    }

    public function setLastUsedAt(?\DateTime $lastUsedAt): static
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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    public function getNetworkId(): ?string
    {
        return $this->networkId;
    }

    public function setNetworkId(?string $networkId): static
    {
        $this->networkId = $networkId;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
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

    public function getOperationalStatus(): ?string
    {
        return $this->operationalStatus;
    }

    public function setOperationalStatus(?string $operationalStatus): static
    {
        $this->operationalStatus = $operationalStatus;

        return $this;
    }

    public function getPurchasePrice(): ?string
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice(?string $purchasePrice): static
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }

    public function getDiscountPct(): ?string
    {
        return $this->discountPct;
    }

    public function setDiscountPct(?string $discountPct): static
    {
        $this->discountPct = $discountPct;

        return $this;
    }

    public function getTemplate(): ?KitTemplate
    {
        return $this->template;
    }

    public function setTemplate(?KitTemplate $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getKitLocation(): ?Location
    {
        return $this->kitLocation;
    }

    public function setKitLocation(?Location $kitLocation): static
    {
        $this->kitLocation = $kitLocation;

        return $this;
    }

    /**
     * @return Collection<int, MaterialUnitHistory>
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }

    public function addHistory(MaterialUnitHistory $history): static
    {
        if (!$this->history->contains($history)) {
            $this->history->add($history);
            $history->setMaterialUnit($this);
        }

        return $this;
    }

    public function removeHistory(MaterialUnitHistory $history): static
    {
        if ($this->history->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getMaterialUnit() === $this) {
                $history->setMaterialUnit(null);
            }
        }

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

    public function getValuation(): float
    {
        $basePrice = $this->purchasePrice ? (float)$this->purchasePrice : ($this->material ? (float)$this->material->getUnitPrice() : 0.0);
        $margin = $this->marginPercentage ? (float)$this->marginPercentage : 0.0;
        $ivaRate = (float)$this->iva;

        $priceWithMargin = $basePrice + ($basePrice * $margin / 100);
        return $priceWithMargin * (1 + $ivaRate / 100);
    }

    public function __toString(): string
    {
        if ($this->alias) {
            return sprintf('%s (%s)', $this->alias, $this->serialNumber ?? 'S/N');
        }
        return sprintf('%s (%s)', $this->material->getName(), $this->serialNumber ?? 'S/N');
    }
}
