<?php

namespace App\Repository;

use App\Entity\AssistanceConfirmation;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for AssistanceConfirmation entities.
 *
 * @extends ServiceEntityRepository<AssistanceConfirmation>
 *
 * @method AssistanceConfirmation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssistanceConfirmation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssistanceConfirmation[]    findAll()
 * @method AssistanceConfirmation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssistanceConfirmationRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry The manager registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssistanceConfirmation::class);
    }

    /**
     * Counts the number of volunteers attending a specific service.
     *
     * @param Service $service The service to count attendees for.
     * @return int The total number of attending volunteers.
     */
    public function countAttendingByService(Service $service): int
    {
        return $this->count([
            'service' => $service,
            'status' => 'attending',
        ]);
    }

    //    /**
    //     * @return AssistanceConfirmation[] Returns an array of AssistanceConfirmation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AssistanceConfirmation
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
