<?php

namespace App\Repository;

use App\Entity\ServiceVehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceVehicle>
 *
 * @method ServiceVehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceVehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceVehicle[]    findAll()
 * @method ServiceVehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceVehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceVehicle::class);
    }
}
