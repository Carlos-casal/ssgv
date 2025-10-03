<?php

namespace App\Repository;

use App\Entity\Volunteer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VolunteerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Volunteer::class);
    }

    public function findBySearchTerm(string $searchTerm): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.name LIKE :searchTerm OR v.email LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('v.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.status = :status')
            ->setParameter('status', $status)
            ->orderBy('v.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countPendingVolunteers(): int
    {
        return (int) $this->createQueryBuilder('v')
            ->select('count(v.id)')
            ->andWhere('v.status = :status')
            ->setParameter('status', Volunteer::STATUS_PENDING)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countActiveVolunteers(): int
    {
        return $this->count(['status' => Volunteer::STATUS_ACTIVE]);
    }

    public function countNewVolunteersThisMonth(): int
    {
        $startDate = new \DateTime('first day of this month 00:00:00');
        $endDate = new \DateTime('last day of this month 23:59:59');

        return (int) $this->createQueryBuilder('v')
            ->select('count(v.id)')
            ->where('v.joinDate BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }
}