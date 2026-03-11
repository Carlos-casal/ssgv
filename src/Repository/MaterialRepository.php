<?php

namespace App\Repository;

use App\Entity\Material;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Material>
 */
class MaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Material::class);
    }

    /**
     * Counts the number of consumable materials with stock below safety threshold.
     */
    public function countLowStockMaterials(): int
    {
        return (int) $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.nature = :nature')
            ->andWhere('m.stock <= (m.safetyStock * COALESCE(m.unitsPerPackage, 1))')
            ->setParameter('nature', \App\Entity\Material::NATURE_CONSUMABLE)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
