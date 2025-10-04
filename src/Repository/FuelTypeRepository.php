<?php

namespace App\Repository;

use App\Entity\FuelType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for FuelType entities.
 *
 * @extends ServiceEntityRepository<FuelType>
 *
 * @method FuelType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FuelType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FuelType[]    findAll()
 * @method FuelType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuelTypeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry The manager registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FuelType::class);
    }
}