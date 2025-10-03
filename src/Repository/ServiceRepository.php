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

    public function findByDate(\DateTimeInterface $date): array
    {
        $startOfDay = (clone $date)->setTime(0, 0, 0);
        $endOfDay = (clone $date)->setTime(23, 59, 59);

        return $this->createQueryBuilder('s')
            ->andWhere('s.startDate >= :start')
            ->andWhere('s.startDate <= :end')
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countServicesThisMonth(): int
    {
        $startDate = new \DateTime('first day of this month 00:00:00');
        $endDate = new \DateTime('last day of this month 23:59:59');

        return (int) $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.startDate BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countCompletedServices(): int
    {
        return (int) $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.endDate < :now')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findCompletedServicesThisYear(): array
    {
        $startDate = new \DateTime('first day of january this year 00:00:00');

        return $this->createQueryBuilder('s')
            ->where('s.endDate < :now')
            ->andWhere('s.startDate >= :start')
            ->setParameter('now', new \DateTime())
            ->setParameter('start', $startDate)
            ->getQuery()
            ->getResult();
    }
}