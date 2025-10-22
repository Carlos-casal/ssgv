<?php

namespace App\Repository;

use App\Entity\PhoneModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhoneModel>
 *
 * @method PhoneModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneModel[]    findAll()
 * @method PhoneModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhoneModel::class);
    }
}
