<?php

namespace App\Repository;

use App\Entity\Ptt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ptt>
 *
 * @method Ptt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ptt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ptt[]    findAll()
 * @method Ptt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PttRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ptt::class);
    }
}
