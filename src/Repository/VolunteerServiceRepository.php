<?php

namespace App\Repository;

use App\Entity\VolunteerService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for VolunteerService entities.
 *
 * @extends ServiceEntityRepository<VolunteerService>
 *
 * @method VolunteerService|null find($id, $lockMode = null, $lockVersion = null)
 * @method VolunteerService|null findOneBy(array $criteria, array $orderBy = null)
 * @method VolunteerService[]    findAll()
 * @method VolunteerService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolunteerServiceRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry The manager registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VolunteerService::class);
    }

    /**
     * Finds all service participations for a given volunteer, ordered by the service start date in descending order.
     *
     * @param \App\Entity\Volunteer $volunteer The volunteer to find services for.
     * @return VolunteerService[] Returns an array of VolunteerService objects.
     */
    public function findForVolunteerOrderedByServiceDate(\App\Entity\Volunteer $volunteer): array
    {
        return $this->createQueryBuilder('vs')
            ->innerJoin('vs.service', 's')
            ->andWhere('vs.volunteer = :volunteer')
            ->setParameter('volunteer', $volunteer)
            ->orderBy('s.startDate', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return VolunteerService[] Returns an array of VolunteerService objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    /**
     * Finds all service participations for a given service, eagerly loading the associated clock-in/out records
     * and ordering them by start time in descending order.
     *
     * @param \App\Entity\Service $service The service to find participations for.
     * @return VolunteerService[] Returns an array of VolunteerService objects with their fichajes.
     */
    public function findByServiceWithOrderedFichajes(\App\Entity\Service $service): array
    {
        return $this->createQueryBuilder('vs')
            ->leftJoin('vs.fichajes', 'f')
            ->addSelect('f')
            ->andWhere('vs.service = :service')
            ->setParameter('service', $service)
            ->orderBy('f.startTime', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
