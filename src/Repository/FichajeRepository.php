<?php

namespace App\Repository;

use App\Entity\Fichaje;
use App\Entity\VolunteerService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for Fichaje (clock-in/out) entities.
 *
 * @extends ServiceEntityRepository<Fichaje>
 *
 * @method Fichaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fichaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fichaje[]    findAll()
 * @method Fichaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichajeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry The manager registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fichaje::class);
    }

    /**
     * Finds a clock-in record that has not yet been closed (i.e., has no end time).
     *
     * @param VolunteerService $volunteerService The volunteer-service association to search within.
     * @return Fichaje|null The open clock-in record, or null if none is found.
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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
