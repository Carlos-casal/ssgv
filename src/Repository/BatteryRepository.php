<?php

namespace App\Repository;

use App\Entity\Battery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Battery>
 *
 * @method Battery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Battery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Battery[]    findAll()
 * @method Battery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BatteryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Battery::class);
    }
}
