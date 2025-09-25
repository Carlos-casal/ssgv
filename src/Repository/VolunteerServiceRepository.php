<?php

namespace App\Repository;

use App\Entity\VolunteerService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VolunteerService>
 */
class VolunteerServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VolunteerService::class);
    }

    /**
     * @return VolunteerService[]
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
     * @return VolunteerService[]
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
