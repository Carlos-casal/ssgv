<?php

namespace App\Service;

use App\Entity\Location;
use App\Entity\Material;
use App\Entity\MaterialBatch;
use App\Entity\MaterialUnit;
use App\Entity\MaterialStock;
use App\Entity\MaterialMovement;
use App\Entity\MaterialUnitHistory;
use App\Entity\Service;
use App\Entity\User;
use App\Entity\Volunteer;
use App\Repository\MaterialRepository;
use App\Repository\MaterialUnitRepository;
use App\Repository\MaterialStockRepository;
use App\Repository\MaterialMovementRepository;
use App\Repository\ServiceMaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class MaterialManager
{
    public function __construct(
        private MaterialRepository $materialRepository,
        private MaterialUnitRepository $unitRepository,
        private MaterialStockRepository $stockRepository,
        private MaterialMovementRepository $movementRepository,
        private ServiceMaterialRepository $serviceMaterialRepository,
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {}

    /**
     * Checks if a technical unit is available for a given time range.
     */
    public function isUnitAvailable(MaterialUnit $unit, \DateTimeInterface $start, \DateTimeInterface $end, ?int $excludeServiceId = null): bool
    {
        if ($unit->isInMaintenance()) {
            return false;
        }

        // Check for overlapping services that have this unit assigned
        // We only consider services that have valid start and end dates
        $qb = $this->serviceMaterialRepository->createQueryBuilder('sm')
            ->join('sm.service', 's')
            ->where('sm.materialUnit = :unit')
            ->andWhere('s.startDate IS NOT NULL')
            ->andWhere('s.endDate IS NOT NULL')
            ->andWhere('s.startDate < :end')
            ->andWhere('s.endDate > :start')
            ->setParameter('unit', $unit)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        if ($excludeServiceId) {
            $qb->andWhere('s.id != :excludeId')
                ->setParameter('excludeId', $excludeServiceId);
        }

        $conflicts = $qb->getQuery()->getResult();

        // A unit is available if there are NO overlapping assignments
        return count($conflicts) === 0;
    }

    /**
     * Suggests the best available units for a material based on the rotation algorithm.
     */
    public function suggestUnits(Material $material, \DateTimeInterface $start, \DateTimeInterface $end, ?int $quantity = null, ?int $excludeServiceId = null): array
    {
        if ($material->getNature() !== Material::NATURE_TECHNICAL) {
            return [];
        }

        $allUnits = $this->unitRepository->findBy(['material' => $material], ['lastUsedAt' => 'ASC']);
        $availableUnits = [];

        foreach ($allUnits as $unit) {
            if ($this->isUnitAvailable($unit, $start, $end, $excludeServiceId)) {
                $availableUnits[] = $unit;
                if ($quantity !== null && count($availableUnits) >= $quantity) {
                    break;
                }
            }
        }

        return $availableUnits;
    }

    /**
     * Counts how many units are available for a given material and timeframe.
     */
    public function countAvailableUnits(Material $material, \DateTimeInterface $start, \DateTimeInterface $end, ?int $excludeServiceId = null): int
    {
        return count($this->suggestUnits($material, $start, $end, null, $excludeServiceId));
    }

    /**
     * Bulk adjusts stock for multiple sizes and records movements.
     */
    public function bulkAdjustStock(Material $material, array $adjustments, string $reason, ?Location $location = null): void
    {
        foreach ($adjustments as $size => $quantity) {
            if ($quantity == 0) continue;
            $this->adjustStock($material, $quantity, $reason, $size, $location);
        }
    }

    /**
     * Adjusts stock for a material and records a movement.
     */
    public function adjustStock(Material $material, int $quantity, string $reason, ?string $size = null, ?Location $location = null, ?Volunteer $responsible = null, ?MaterialBatch $batch = null): void
    {
        /** @var User|null $currentUser */
        $currentUser = $this->security->getUser();

        if (!$location) {
            $location = $this->getCentralWarehouse();
        }

        if ($quantity < 0 && !$batch && $material->getNature() === Material::NATURE_CONSUMABLE) {
            // Use FIFO for negative adjustments if batch is not specified
            $this->transfer($material, $location, null, abs($quantity), $reason, $responsible, $size, null, null);
            return;
        }

        $movement = new MaterialMovement();
        $movement->setMaterial($material);
        $movement->setQuantity(abs($quantity));
        $movement->setReason($reason);
        $movement->setSize($size);
        $movement->setUser($currentUser);
        $movement->setDestination($quantity > 0 ? $location : null);
        $movement->setOrigin($quantity < 0 ? $location : null);
        $movement->setResponsible($responsible);
        $movement->setBatch($batch);
        $this->entityManager->persist($movement);

        $this->updateStockWithBatch($material, $location, $quantity, $batch, $size);

        $this->entityManager->flush();
    }

    /**
     * Creates a new material unit and updates global stock.
     */
    public function createUnit(Material $material, array $data, ?Location $location = null): MaterialUnit
    {
        $unit = new MaterialUnit();
        $unit->setMaterial($material);
        $unit->setCollectiveNumber($data['collectiveNumber'] ?? null);
        $unit->setSerialNumber($data['serialNumber'] ?? null);
        $unit->setAlias($data['alias'] ?? null);
        $unit->setNetworkId($data['networkId'] ?? null);
        $unit->setPhoneNumber($data['phoneNumber'] ?? null);
        $unit->setPttStatus($data['pttStatus'] ?? 'OK');
        $unit->setCoverStatus($data['coverStatus'] ?? 'OK');
        $unit->setBatteryStatus($data['batteryStatus'] ?? '100%');
        $unit->setLocation($location ?: $this->getCentralWarehouse());

        if (isset($data['purchasePrice'])) $unit->setPurchasePrice($data['purchasePrice']);
        if (isset($data['discountPct'])) $unit->setDiscountPct($data['discountPct']);

        $this->entityManager->persist($unit);

        // Update global stock for technical equipment
        $material->setStock($material->getStock() + 1);

        return $unit;
    }

    /**
     * Entry from supplier to a location
     */
    public function entry(Material $material, Location $destination, int $quantity, ?Volunteer $responsible, ?string $size = null): void
    {
        $this->adjustStock($material, $quantity, 'Entrada de proveedor', $size, $destination, $responsible);
    }

    /**
     * Transfer between two locations or handle Entry/Exit
     * Implements FIFO for consumables if batch is not specified.
     */
    public function transfer(
        Material $material,
        ?Location $origin,
        ?Location $destination,
        int $quantity,
        string $reason,
        ?Volunteer $responsible,
        ?string $size = null,
        ?MaterialUnit $unit = null,
        ?MaterialBatch $batch = null
    ): void {
        /** @var User|null $currentUser */
        $currentUser = $this->security->getUser();

        if ($material->getNature() === Material::NATURE_TECHNICAL && $unit) {
            if ($destination) {
                $unit->setLocation($destination);
            }
            $this->recordMovement($material, $quantity, $reason, $origin, $destination, $responsible, $size, $batch);
        } elseif ($material->getNature() === Material::NATURE_CONSUMABLE && !$batch && $origin) {
            // FIFO logic for subtraction
            $remainingToSubtract = $quantity;
            $batches = $this->entityManager->getRepository(MaterialBatch::class)->findBy(
                ['material' => $material],
                ['expirationDate' => 'ASC', 'createdAt' => 'ASC']
            );

            foreach ($batches as $b) {
                $stock = $this->stockRepository->findOneBy([
                    'material' => $material,
                    'location' => $origin,
                    'batch' => $b,
                    'size' => $size ?: 'UNICA'
                ]);

                if ($stock && $stock->getQuantity() > 0) {
                    $toSubtract = min($remainingToSubtract, $stock->getQuantity());
                    $this->updateStockWithBatch($material, $origin, -$toSubtract, $b, $size);
                    if ($destination) {
                        $this->updateStockWithBatch($material, $destination, $toSubtract, $b, $size);
                    }
                    $this->recordMovement($material, $toSubtract, $reason, $origin, $destination, $responsible, $size, $b);

                    $remainingToSubtract -= $toSubtract;
                    if ($remainingToSubtract <= 0) break;
                }
            }

            // If still remaining, subtract from "no batch" stock if any
            if ($remainingToSubtract > 0) {
                $this->updateStockWithBatch($material, $origin, -$remainingToSubtract, null, $size);
                if ($destination) {
                    $this->updateStockWithBatch($material, $destination, $remainingToSubtract, null, $size);
                }
                $this->recordMovement($material, $remainingToSubtract, $reason, $origin, $destination, $responsible, $size, null);
            }
        } else {
            // Explicit batch or entry from null origin
            if ($origin) {
                $this->updateStockWithBatch($material, $origin, -$quantity, $batch, $size);
            }
            if ($destination) {
                $this->updateStockWithBatch($material, $destination, $quantity, $batch, $size);
            }
            $this->recordMovement($material, $quantity, $reason, $origin, $destination, $responsible, $size, $batch);
        }

        $this->entityManager->flush();
    }

    private function recordMovement(
        Material $material,
        int $quantity,
        string $reason,
        ?Location $origin,
        ?Location $destination,
        ?Volunteer $responsible,
        ?string $size = null,
        ?MaterialBatch $batch = null
    ): void {
        /** @var User|null $currentUser */
        $currentUser = $this->security->getUser();

        $movement = new MaterialMovement();
        $movement->setMaterial($material);
        $movement->setQuantity(abs($quantity));
        $movement->setReason($reason);
        $movement->setOrigin($origin);
        $movement->setDestination($destination);
        $movement->setResponsible($responsible);
        $movement->setUser($currentUser);
        $movement->setSize($size);
        $movement->setBatch($batch);

        $this->entityManager->persist($movement);
    }

    public function updateStockDirectly(Material $material, Location $location, int $delta, ?string $size = null): void
    {
        $this->updateStockWithBatch($material, $location, $delta, null, $size);
    }

    public function updateStockWithBatch(Material $material, Location $location, int $delta, ?\App\Entity\MaterialBatch $batch = null, ?string $size = null): void
    {
        if ($size === null) $size = 'UNICA';

        $criteria = [
            'material' => $material,
            'location' => $location,
            'size' => $size,
            'batch' => $batch
        ];

        $stock = $this->stockRepository->findOneBy($criteria);

        if (!$stock) {
            $stock = new MaterialStock();
            $stock->setMaterial($material);
            $stock->setLocation($location);
            $stock->setSize($size);
            $stock->setBatch($batch);
            $stock->setQuantity(0);
            $this->entityManager->persist($stock);
        }

        $stock->setQuantity($stock->getQuantity() + $delta);

        // Robust global stock sync (applies to both Consumables and Technical bulk stock)
        $material->setStock($material->getStock() + $delta);
    }

    /**
     * Checks if a consumable has enough stock.
     */
    public function hasEnoughStock(Material $material, int $requestedQuantity, ?string $size = null, ?Location $location = null): bool
    {
        if ($material->getNature() !== Material::NATURE_CONSUMABLE) {
            return true;
        }

        if ($location) {
            $stock = $this->stockRepository->findOneBy(['material' => $material, 'size' => $size, 'location' => $location]);
            return $stock && $stock->getQuantity() >= $requestedQuantity;
        }

        if ($size) {
            $stock = $this->stockRepository->findOneBy(['material' => $material, 'size' => $size]);
            return $stock && $stock->getQuantity() >= $requestedQuantity;
        }

        return $material->getStock() >= $requestedQuantity;
    }

    /**
     * Gets materials that need replenishment.
     */
    public function getMaterialsNeedingReplenishment(): array
    {
        return $this->materialRepository->createQueryBuilder('m')
            ->where('m.nature = :nature')
            ->andWhere('m.stock <= (m.safetyStock * COALESCE(m.unitsPerPackage, 1))')
            ->setParameter('nature', Material::NATURE_CONSUMABLE)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns the Central Warehouse location.
     * Throws an exception if not found, as it should be created during system setup.
     */
    public function getCentralWarehouse(): Location
    {
        $warehouse = $this->entityManager->getRepository(Location::class)->findOneBy(['type' => Location::TYPE_WAREHOUSE]);

        if (!$warehouse) {
            // Auto-create central warehouse to avoid crashes during initial usage
            $warehouse = new Location();
            $warehouse->setName('Almacén Central');
            $warehouse->setType(Location::TYPE_WAREHOUSE);
            $this->entityManager->persist($warehouse);
            $this->entityManager->flush();
        }

        return $warehouse;
    }

    /**
     * Changes the operational status of a unit and logs it to history
     */
    public function changeUnitStatus(MaterialUnit $unit, string $status, ?string $reason): void
    {
        /** @var User|null $currentUser */
        $currentUser = $this->security->getUser();

        $history = new MaterialUnitHistory();
        $history->setMaterialUnit($unit);
        $history->setStatus($status);
        $history->setReason($reason);
        $history->setUser($currentUser);

        $this->entityManager->persist($history);

        $unit->setOperationalStatus($status);

        // Depending on status, we might want to automatically set isInMaintenance to true
        if (in_array($status, ['EN REPARACION', 'AVERIADO'])) {
            $unit->setIsInMaintenance(true);
        } else if ($status === 'OPERATIVO') {
            $unit->setIsInMaintenance(false);
        }

        $this->entityManager->flush();
    }
}
