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

    public function createPaginatorQueryBuilder(string $searchTerm = '', string $filterStatus = 'all')
    {
        $queryBuilder = $this->createQueryBuilder('v')
            ->leftJoin('v.user', 'u') // Asume que Volunteer tiene una relación con User
            ->addSelect('u'); // Para poder buscar por email de usuario, si es necesario

        // Aplica filtros de búsqueda si hay un término
        if ($searchTerm) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('v.name', ':search'),
                    $queryBuilder->expr()->like('v.lastName', ':search'),
                    $queryBuilder->expr()->like('v.dni', ':search'),
                    $queryBuilder->expr()->like('u.email', ':search') // Búsqueda por email del usuario
                )
            )
            ->setParameter('search', '%' . $searchTerm . '%');
        }

        // Aplica filtros por estado si no es "all"
        if ($filterStatus !== 'all') {
            $queryBuilder->andWhere('v.status = :status')
                         ->setParameter('status', $filterStatus);
        }

        // Ordena los resultados para una paginación consistente
        $queryBuilder->orderBy('v.id', 'ASC');

        return $queryBuilder;
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

    public function getStatusStats(): array
    {
        $results = $this->createQueryBuilder('v')
            ->select('v.status, COUNT(v.id) as status_count')
            ->groupBy('v.status')
            ->getQuery()
            ->getScalarResult();

        $stats = [
            'total' => 0,
            Volunteer::STATUS_ACTIVE => 0,
            Volunteer::STATUS_SUSPENDED => 0,
            Volunteer::STATUS_INACTIVE => 0,
        ];

        foreach ($results as $result) {
            if (isset($stats[$result['status']])) {
                $stats[$result['status']] = (int) $result['status_count'];
                $stats['total'] += (int) $result['status_count'];
            }
        }

        return $stats;
    }
}