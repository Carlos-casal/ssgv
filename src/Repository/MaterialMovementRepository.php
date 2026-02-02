<?php

namespace App\Repository;

use App\Entity\MaterialMovement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaterialMovement>
 *
 * @method MaterialMovement|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialMovement|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialMovement[]    findAll()
 * @method MaterialMovement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialMovementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialMovement::class);
    }
}
