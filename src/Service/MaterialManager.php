<?php

namespace App\Service;

use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Entity\MaterialStock;
use App\Entity\MaterialMovement;
use App\Entity\Service;
use App\Entity\User;
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
     * Adjusts stock for a material and records a movement.
     */
    public function adjustStock(Material $material, int $quantity, string $reason, ?string $size = null): void
    {
        /** @var User|null $currentUser */
        $currentUser = $this->security->getUser();

        $movement = new MaterialMovement();
        $movement->setMaterial($material);
        $movement->setQuantity($quantity);
        $movement->setReason($reason);
        $movement->setSize($size);
        $movement->setUser($currentUser);
        $this->entityManager->persist($movement);

        if ($size) {
            $stock = $this->stockRepository->findOneBy(['material' => $material, 'size' => $size]);
            if (!$stock) {
                $stock = new MaterialStock();
                $stock->setMaterial($material);
                $stock->setSize($size);
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
     * Checks if a consumable has enough stock.
     */
    public function hasEnoughStock(Material $material, int $requestedQuantity, ?string $size = null): bool
    {
        if ($material->getNature() !== Material::NATURE_CONSUMABLE) {
            return true;
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
}
