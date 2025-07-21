<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * Obtiene todos los servicios activos (ejemplo si tuvieras un campo booleano llamado isActive)
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.startDate <= :now')
            ->andWhere('s.endDate >= :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('s.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtiene los servicios por categoría
     */
    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.category = :cat')
            ->setParameter('cat', $category)
            ->orderBy('s.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Busca servicios que coincidan con parte del título
     */
    public function searchByTitle(string $query): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.title LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->orderBy('s.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findServicesByVolunteer($volunteer): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.assistanceConfirmations', 'ac')
            ->andWhere('ac.volunteer = :volunteer')
            ->andWhere('ac.hasAttended = :hasAttended')
            ->setParameter('volunteer', $volunteer)
            ->setParameter('hasAttended', true)
            ->getQuery()
            ->getResult()
        ;
    }
}