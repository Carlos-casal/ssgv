<?php

namespace App\Repository;

use App\Entity\MaterialUnit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaterialUnit>
 *
 * @method MaterialUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialUnit[]    findAll()
 * @method MaterialUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialUnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialUnit::class);
    }
}
