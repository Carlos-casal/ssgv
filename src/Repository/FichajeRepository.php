<?php

namespace App\Repository;

use App\Entity\Fichaje;
use App\Entity\VolunteerService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fichaje>
 *
 * @method Fichaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fichaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fichaje[]    findAll()
 * @method Fichaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fichaje::class);
    }

    public function findOpenFichaje(VolunteerService $volunteerService): ?Fichaje
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.volunteerService = :volunteerService')
            ->andWhere('f.endTime IS NULL')
            ->setParameter('volunteerService', $volunteerService)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
