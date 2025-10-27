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

    /**
     * Checks if a Fichaje record already exists for a given volunteer and exact date range.
     *
     * @param \App\Entity\Volunteer $volunteer
     * @param \DateTimeInterface $startTime
     * @param \DateTimeInterface $endTime
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function existsForVolunteerInDateRange(\App\Entity\Volunteer $volunteer, \DateTimeInterface $startTime, \DateTimeInterface $endTime): bool
    {
        $count = $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->innerJoin('f.volunteerService', 'vs')
            ->andWhere('vs.volunteer = :volunteer')
            ->andWhere('f.startTime = :startTime')
            ->andWhere('f.endTime = :endTime')
            ->setParameter('volunteer', $volunteer)
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }
}
