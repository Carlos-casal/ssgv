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
     * Finds consumable materials with stock below safety threshold.
     * Logic: (stock / unitsPerPackage) <= safetyStock
     */
    public function findLowStockMaterials(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.nature = :nature')
            ->andWhere('m.stock <= (m.safetyStock * COALESCE(m.unitsPerPackage, 1))')
            ->setParameter('nature', \App\Entity\Material::NATURE_CONSUMABLE)
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds materials with expiration issues.
     * Status 'red': expired
     * Status 'orange': expiring within 30 days
     */
    public function findExpiringMaterials(\DateTimeImmutable $threshold): array
    {
        // This is complex because of batches. For now, let's at least optimize the simple case
        // and fetch materials that either have a global expiration date or at least one batch with expiration issues.

        $qb = $this->createQueryBuilder('m');
        $qb->leftJoin('m.batches', 'b')
           ->where($qb->expr()->orX(
               'm.expirationDate <= :threshold',
               'b.expirationDate <= :threshold'
           ))
           ->setParameter('threshold', $threshold);

        return $qb->getQuery()->getResult();
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

    /**
     * Returns all unique categories present in the material database.
     */
    public function findAllExistingCategories(): array
    {
        $results = $this->createQueryBuilder('m')
            ->select('DISTINCT m.category')
            ->where('m.category IS NOT NULL')
            ->orderBy('m.category', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return array_column($results, 'category');
    }

    /**
     * Returns all unique natures present in the material database.
     */
    public function findAllExistingNatures(): array
    {
        $results = $this->createQueryBuilder('m')
            ->select('DISTINCT m.nature')
            ->where('m.nature IS NOT NULL')
            ->orderBy('m.nature', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return array_column($results, 'nature');
    }

    /**
     * Returns all unique subfamilies present in the material database.
     */
    public function findAllExistingSubFamilies(?string $category = null): array
    {
        $qb = $this->createQueryBuilder('m')
            ->select('DISTINCT m.subFamily')
            ->where('m.subFamily IS NOT NULL')
            ->orderBy('m.subFamily', 'ASC');

        if ($category) {
            $qb->andWhere('m.category = :category')
               ->setParameter('category', $category);
        }

        $results = $qb->getQuery()->getScalarResult();

        return array_column($results, 'subFamily');
    }
}
