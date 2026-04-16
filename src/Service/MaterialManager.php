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
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

class MaterialManager
{
    private EntityManagerInterface $entityManager;
    private array $recordedMovementsCache = [];

    public function __construct(
        private MaterialRepository $materialRepository,
        private MaterialUnitRepository $unitRepository,
        private MaterialStockRepository $stockRepository,
        private MaterialMovementRepository $movementRepository,
        private ServiceMaterialRepository $serviceMaterialRepository,
        private ManagerRegistry $managerRegistry,
        private Security $security,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    private function getEntityManager(): EntityManagerInterface
    {
        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->managerRegistry->resetManager();
        }
        return $this->entityManager;
    }

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
            $location = $this->getDefaultLocation($material);
        } else {
            // If the user explicitly selects Central Warehouse, but it belongs to a specialized one:
            $central = $this->getCentralWarehouse();
            $default = $this->getDefaultLocation($material);
            if ($location->getId() === $central->getId() && $default->getId() !== $central->getId()) {
                $location = $default;
            }
        }

        if ($quantity < 0 && !$batch && $material->getNature() === Material::NATURE_CONSUMABLE) {
            // Use FIFO for negative adjustments if batch is not specified
            $this->transfer($material, $location, null, abs($quantity), $reason, $responsible, $size, null, null);
            return;
        }

        // Standardize Entry reasons (Tarea 3)
        if ($quantity > 0 && (str_contains($reason, 'Inicialización') || str_contains($reason, 'proveedor') || str_contains($reason, 'Entrada'))) {
            $reason = sprintf('Entrada: Registro Inicial / %s', $location->getName());
        }

        $this->recordMovement(
            $material,
            abs($quantity),
            $reason,
            $quantity < 0 ? $location : null,
            $quantity > 0 ? $location : null,
            $responsible,
            $size,
            $batch,
            new \DateTimeImmutable(),
            $quantity < 0
        );

        $this->updateStockWithBatch($material, $location, $quantity, $batch, $size);
    }

    /**
     * Creates a new material unit and updates global and local stock.
     */
    public function createUnit(Material $material, array $data, ?Location $location = null): MaterialUnit
    {
        $finalLocation = $location ?: $this->getDefaultLocation($material);
        $reason = sprintf('Entrada: Registro Inicial / %s', $finalLocation->getName());

        $unit = new MaterialUnit();
        $unit->setMaterial($material);
        $unit->setCollectiveNumber($data['collectiveNumber'] ?? null);
        $unit->setSerialNumber($data['serial_number'] ?? $data['serialNumber'] ?? null);
        $unit->setAlias($data['alias'] ?? null);
        $unit->setNetworkId($data['network_id'] ?? $data['networkId'] ?? null);
        $unit->setPhoneNumber($data['phone_number'] ?? $data['phoneNumber'] ?? null);
        $unit->setPttStatus($data['ptt_status'] ?? $data['pttStatus'] ?? 'OK');
        $unit->setCoverStatus($data['cover_status'] ?? $data['coverStatus'] ?? 'OK');
        $unit->setBatteryStatus($data['battery_status'] ?? $data['batteryStatus'] ?? '100%');

        $unit->setLocation($finalLocation);

        if (isset($data['purchasePrice'])) $unit->setPurchasePrice($data['purchasePrice']);
        if (isset($data['discountPct'])) $unit->setDiscountPct($data['discountPct']);

        $this->getEntityManager()->persist($unit);
        $this->getEntityManager()->flush(); // Garantiza que la unidad tenga ID antes de recordMovement

        // Synchronize stock for this unit in the location
        $this->updateStockWithBatch($material, $finalLocation, 1, null, 'UNICA');

        // Log initial entry
        $this->recordMovement($material, 1, $reason, null, $finalLocation, null, 'UNICA', null, new \DateTimeImmutable(), false, $unit);

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
        // Auto-route specialized materials if origin or destination is Central Warehouse
        $central = $this->getCentralWarehouse();
        $default = $this->getDefaultLocation($material);
        if ($destination && $destination->getId() === $central->getId() && $default->getId() !== $central->getId()) {
            $destination = $default;
        }
        if ($origin && $origin->getId() === $central->getId() && $default->getId() !== $central->getId()) {
            $origin = $default;
        }

        $now = new \DateTimeImmutable();

        // Define clean standardized reasons
        $entryReason = $destination ? sprintf('Entrada: Registro Inicial / %s', $destination->getName()) : 'Entrada: Registro Inicial';
        $transferReason = ($origin && $destination) ? sprintf('Traspaso: %s -> %s', $origin->getName(), $destination->getName()) : $reason;

        if ($material->getNature() === Material::NATURE_TECHNICAL && $unit) {
            // Technical Unit Transfer (Strict Logic)
            $currentLocation = $unit->getLocation();

            if ($currentLocation) {
                // Withdrawal from origin
                $this->updateStockWithBatch($material, $currentLocation, -$quantity, null, $size);

                if ($destination) {
                    // Entry to destination
                    $unit->setLocation($destination);
                    $this->updateStockWithBatch($material, $destination, $quantity, null, $size);
                } else {
                    $unit->setLocation(null);
                }

                // Single record for transfer or exit
                $this->recordMovement($material, $quantity, $transferReason, $currentLocation, $destination, $responsible, $size, $batch, $now, $destination === null, $unit);
            } else {
                // It's a new entry (Registration)
                if ($destination) {
                    $unit->setLocation($destination);
                    $this->updateStockWithBatch($material, $destination, $quantity, null, $size);
                    $this->recordMovement($material, $quantity, $entryReason, null, $destination, $responsible, $size, $batch, $now, false, $unit);
                }
            }
        } elseif ($material->getNature() === Material::NATURE_CONSUMABLE && !$batch && $origin) {
            // FIFO logic for subtraction (Always a transfer because origin exists)
            $remainingToSubtract = $quantity;
            $batches = $this->getEntityManager()->getRepository(MaterialBatch::class)->findBy(
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

                    // Single record for the specific batch transfer/exit
                    $this->recordMovement($material, $toSubtract, $transferReason, $origin, $destination, $responsible, $size, $b, $now, $destination === null);

                    $remainingToSubtract -= $toSubtract;
                    if ($remainingToSubtract <= 0) break;
                }
            }

            if ($remainingToSubtract > 0) {
                $this->updateStockWithBatch($material, $origin, -$remainingToSubtract, null, $size);
                if ($destination) {
                    $this->updateStockWithBatch($material, $destination, $remainingToSubtract, null, $size);
                }
                // Single record for the remaining (no batch) transfer/exit
                $this->recordMovement($material, $remainingToSubtract, $transferReason, $origin, $destination, $responsible, $size, null, $now, $destination === null);
            }
        } else {
            // Explicit batch or entry from null origin (Registration/Initial Entry)
            if ($origin) {
                $this->updateStockWithBatch($material, $origin, -$quantity, $batch, $size);
            }

            if ($destination) {
                $this->updateStockWithBatch($material, $destination, $quantity, $batch, $size);
            }

            $finalReason = $origin ? $transferReason : $entryReason;
            $this->recordMovement($material, $quantity, $finalReason, $origin, $destination, $responsible, $size, $batch, $now, $destination === null && $origin !== null);
        }

    }

    private function recordMovement(
        Material $material,
        int $quantity,
        string $reason,
        ?Location $origin,
        ?Location $destination,
        ?Volunteer $responsible,
        ?string $size = null,
        ?MaterialBatch $batch = null,
        ?\DateTimeImmutable $createdAt = null,
        bool $isWithdrawal = false,
        ?MaterialUnit $unit = null
    ): void {
        /** @var User|null $currentUser */
        $currentUser = $this->security->getUser();
        $timestamp = $createdAt ?: new \DateTimeImmutable();
        $netQuantity = $isWithdrawal ? -abs($quantity) : abs($quantity);

        // If it's a transfer between two locations, both records should mention origin and destination
        // but only the quantity changes sign.

        // Standardize History Format (Tarea 3)
        // Ensure transfers use strictly "Traspaso: [Origen] -> [Destino]"
        if ($origin && $destination && !str_starts_with($reason, 'Entrada') && !str_starts_with($reason, 'Consumo')) {
            $reason = sprintf('Traspaso: %s -> %s', $origin->getName(), $destination->getName());
        }

        // IDEMPOTENCY CHECK (Tarea 2)
        // 1. Check Request Cache (for records not yet flushed)
        $cacheKey = sprintf(
            '%s_%d_%s_%s_%s_%s_%s',
            $material->getId() ?? spl_object_hash($material),
            $netQuantity,
            md5($reason),
            $origin ? ($origin->getId() ?? spl_object_hash($origin)) : 'null',
            $destination ? ($destination->getId() ?? spl_object_hash($destination)) : 'null',
            $unit ? ($unit->getId() ?? spl_object_hash($unit)) : 'null',
            $timestamp->format('Y-m-d_H:i')
        );

        if (isset($this->recordedMovementsCache[$cacheKey])) {
            return;
        }

        // 2. Check Database (for records already persisted in the same minute)
        $minuteStart = $timestamp->modify('midnight')->add(new \DateInterval('PT' . ($timestamp->format('H') * 3600 + $timestamp->format('i') * 60) . 'S'));

        $qb = $this->movementRepository->createQueryBuilder('m')
            ->where('m.material = :material')
            ->andWhere('m.quantity = :quantity')
            ->andWhere('m.reason = :reason')
            ->andWhere('m.createdAt >= :minuteStart')
            ->andWhere('m.createdAt <= :timestamp')
            ->setParameter('material', $material)
            ->setParameter('quantity', $netQuantity)
            ->setParameter('reason', $reason)
            ->setParameter('minuteStart', $minuteStart)
            ->setParameter('timestamp', $timestamp);

        if ($origin) {
            $qb->andWhere('m.origin = :origin')->setParameter('origin', $origin);
        } else {
            $qb->andWhere('m.origin IS NULL');
        }

        if ($destination) {
            $qb->andWhere('m.destination = :destination')->setParameter('destination', $destination);
        } else {
            $qb->andWhere('m.destination IS NULL');
        }

        if ($unit) {
            $qb->andWhere('m.materialUnit = :unit')->setParameter('unit', $unit);
        } else {
            $qb->andWhere('m.materialUnit IS NULL');
        }

        if ($batch) {
            $qb->andWhere('m.batch = :batch')->setParameter('batch', $batch);
        }

        $existing = $qb->getQuery()->getResult();
        if (count($existing) > 0) {
            $this->recordedMovementsCache[$cacheKey] = true;
            return;
        }

        $this->recordedMovementsCache[$cacheKey] = true;

        $movement = new MaterialMovement();
        $movement->setMaterial($material);
        $movement->setQuantity($netQuantity);
        $movement->setReason($reason);
        $movement->setOrigin($origin);
        $movement->setDestination($destination);
        $movement->setResponsible($responsible);
        $movement->setUser($currentUser);
        $movement->setSize($size);
        $movement->setBatch($batch);
        $movement->setMaterialUnit($unit);
        $movement->setCreatedAt($timestamp);

        $this->getEntityManager()->persist($movement);
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
            $this->getEntityManager()->persist($stock);
        }

        $stock->setQuantity($stock->getQuantity() + $delta);

        // Robust global stock sync (applies to both Consumables and Technical bulk stock)
        $material->setStock($material->getStock() + $delta);
    }

    /**
     * Returns total stock available for a consumable, excluding items in KITS.
     */
    public function getAvailableStock(Material $material, ?string $size = null): int
    {
        if ($material->getNature() !== Material::NATURE_CONSUMABLE) {
            return 0;
        }

        $qb = $this->stockRepository->createQueryBuilder('ms')
            ->select('SUM(ms.quantity)')
            ->join('ms.location', 'l')
            ->where('ms.material = :material')
            ->andWhere('l.type != :kitType')
            ->setParameter('material', $material)
            ->setParameter('kitType', Location::TYPE_KIT);

        if ($size) {
            $qb->andWhere('ms.size = :size')
                ->setParameter('size', $size);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
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
     * Returns the Default Location for a material based on its category.
     */
    public function getDefaultLocation(Material $material): Location
    {
        if ($material->getCategory() === 'Sanitario') {
            return $this->getPharmacyWarehouse();
        }

        if ($material->getCategory() === 'Comunicaciones') {
            return $this->getCecomWarehouse();
        }

        return $this->getCentralWarehouse();
    }

    /**
     * Returns the Pharmacy Warehouse location (Almacén Farmacia).
     */
    public function getPharmacyWarehouse(): Location
    {
        if (!$this->getEntityManager()->isOpen()) {
            throw new \RuntimeException("EntityManager is closed. Cannot retrieve Pharmacy Warehouse.");
        }

        $warehouse = $this->getEntityManager()->getRepository(Location::class)->findOneBy(['name' => 'Almacén Farmacia']);

        if (!$warehouse) {
            $warehouse = new Location();
            $warehouse->setName('Almacén Farmacia');
            $warehouse->setType(Location::TYPE_WAREHOUSE);
            $this->getEntityManager()->persist($warehouse);
            $this->getEntityManager()->flush();
        }

        return $warehouse;
    }

    /**
     * Returns the Central Warehouse location.
     */
    public function getCentralWarehouse(): Location
    {
        if (!$this->getEntityManager()->isOpen()) {
            throw new \RuntimeException("EntityManager is closed. Cannot retrieve Central Warehouse.");
        }

        $warehouse = $this->getEntityManager()->getRepository(Location::class)->findOneBy(['name' => 'Almacén Central']);

        if (!$warehouse) {
            // Priority 1: Check by name if findOneBy type returned wrong one
            $warehouse = $this->getEntityManager()->getRepository(Location::class)->findOneBy(['name' => 'Almacén Central']);
            
            if (!$warehouse) {
                // Priority 2: Check by type alone as fallback
                $warehouse = $this->getEntityManager()->getRepository(Location::class)->findOneBy(['type' => Location::TYPE_WAREHOUSE]);
            }

            if (!$warehouse) {
                $warehouse = new Location();
                $warehouse->setName('Almacén Central');
                $warehouse->setType(Location::TYPE_WAREHOUSE);
                $this->getEntityManager()->persist($warehouse);
                $this->getEntityManager()->flush();
            }
        }

        return $warehouse;
    }
    /**
     * Returns the CECOM Warehouse location (Almacén CECOM).
     */
    public function getCecomWarehouse(): Location
    {
        if (!$this->getEntityManager()->isOpen()) {
            throw new \RuntimeException("EntityManager is closed. Cannot retrieve CECOM Warehouse.");
        }

        $warehouse = $this->getEntityManager()->getRepository(Location::class)->findOneBy(['name' => 'Almacén CECOM']);

        if (!$warehouse) {
            $warehouse = new Location();
            $warehouse->setName('Almacén CECOM');
            $warehouse->setType(Location::TYPE_WAREHOUSE);
            $this->getEntityManager()->persist($warehouse);
            $this->getEntityManager()->flush();
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

        $this->getEntityManager()->persist($history);

        $unit->setOperationalStatus($status);

        // Depending on status, we might want to automatically set isInMaintenance to true
        if (in_array($status, ['EN REPARACION', 'AVERIADO'])) {
            $unit->setIsInMaintenance(true);
        } else if ($status === 'OPERATIVO') {
            $unit->setIsInMaintenance(false);
        }
    }
}
