<?php

namespace App\Repository;

use App\Entity\MaterialBatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaterialBatch>
 *
 * @method MaterialBatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialBatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialBatch[]    findAll()
 * @method MaterialBatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialBatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialBatch::class);
    }
}
