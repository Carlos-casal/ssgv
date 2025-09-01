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
}