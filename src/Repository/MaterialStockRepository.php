<?php

namespace App\Repository;

use App\Entity\MaterialStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaterialStock>
 *
 * @method MaterialStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialStock[]    findAll()
 * @method MaterialStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialStock::class);
    }
}
