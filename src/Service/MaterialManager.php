<?php

namespace App\Service;

use App\Entity\Location;
use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Entity\MaterialStock;
use App\Entity\MaterialMovement;
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
        $qb = $this->serviceMaterialRepository->createQueryBuilder('sm')
            ->join('sm.service', 's')
            ->where('sm.materialUnit = :unit')
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
    public function adjustStock(Material $material, int $quantity, string $reason, ?string $size = null, ?Location $location = null, ?Volunteer $responsible = null): void
    {
        /** @var User|null $currentUser */
        $currentUser = $this->security->getUser();

        if (!$location) {
            $location = $this->getCentralWarehouse();
        }

        $movement = new MaterialMovement();
        $movement->setMaterial($material);
        $movement->setQuantity($quantity);
        $movement->setReason($reason);
        $movement->setSize($size);
        $movement->setUser($currentUser);
        $movement->setDestination($quantity > 0 ? $location : null);
        $movement->setOrigin($quantity < 0 ? $location : null);
        $movement->setResponsible($responsible);
        $this->entityManager->persist($movement);

        if ($size || $location) {
            $stock = $this->stockRepository->findOneBy([
                'material' => $material,
                'size' => $size,
                'location' => $location
            ]);
            if (!$stock) {
                $stock = new MaterialStock();
                $stock->setMaterial($material);
                $stock->setSize($size);
                $stock->setLocation($location);
                $this->entityManager->persist($stock);
            }
            $stock->setQuantity($stock->getQuantity() + $quantity);
        }

        // Sync global stock if it's not a technical unit
        if ($material->getNature() === Material::NATURE_CONSUMABLE) {
            $material->setStock($material->getStock() + $quantity);
        }

        $this->entityManager->flush();
    }

    /**
     * Entry from supplier to a location
     */
    public function entry(Material $material, Location $destination, int $quantity, ?Volunteer $responsible, ?string $size = null): void
    {
        $this->adjustStock($material, $quantity, 'Entrada de proveedor', $size, $destination, $responsible);
    }

    /**
     * Transfer between two locations
     */
    public function transfer(
        Material $material,
        Location $origin,
        Location $destination,
        int $quantity,
        string $reason,
        ?Volunteer $responsible,
        ?string $size = null,
        ?MaterialUnit $unit = null
    ): void {
        /** @var User|null $currentUser */
        $currentUser = $this->security->getUser();

        if ($material->getNature() === Material::NATURE_TECHNICAL && $unit) {
            $unit->setLocation($destination);
        } else {
            // Origin subtraction
            $this->updateStockDirectly($material, $origin, -$quantity, $size);
            // Destination addition
            $this->updateStockDirectly($material, $destination, $quantity, $size);
        }

        // Record movement
        $movement = new MaterialMovement();
        $movement->setMaterial($material);
        $movement->setQuantity($quantity);
        $movement->setReason($reason);
        $movement->setOrigin($origin);
        $movement->setDestination($destination);
        $movement->setResponsible($responsible);
        $movement->setUser($currentUser);
        $movement->setSize($size);

        $this->entityManager->persist($movement);
        $this->entityManager->flush();
    }

    private function updateStockDirectly(Material $material, Location $location, int $delta, ?string $size = null): void
    {
        $stock = $this->stockRepository->findOneBy([
            'material' => $material,
            'location' => $location,
            'size' => $size
        ]);

        if (!$stock) {
            $stock = new MaterialStock();
            $stock->setMaterial($material);
            $stock->setLocation($location);
            $stock->setSize($size);
            $stock->setQuantity(0);
            $this->entityManager->persist($stock);
        }

        $stock->setQuantity($stock->getQuantity() + $delta);

        // Global stock sync for consumables
        if ($material->getNature() === Material::NATURE_CONSUMABLE) {
            $material->setStock($material->getStock() + $delta);
        }
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
            ->andWhere('m.stock <= m.safetyStock')
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
            throw new \RuntimeException('El Almacén Central no está configurado. Por favor, créelo en la sección de Ubicaciones.');
        }

        return $warehouse;
    }
}
