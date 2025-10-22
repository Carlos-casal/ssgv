<?php

namespace App\Repository;

use App\Entity\Talkie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Talkie>
 *
 * @method Talkie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Talkie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Talkie[]    findAll()
 * @method Talkie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TalkieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Talkie::class);
    }
}
