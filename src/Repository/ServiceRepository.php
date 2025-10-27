<?php 

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for Service entities.
 *
 * @extends ServiceEntityRepository<Service>
 *
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry The manager registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * Finds all services that are currently active (i.e., the current date is between the start and end dates).
     * @return Service[] Returns an array of active Service objects.
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
     * Finds all services belonging to a specific category.
     * @param string $category The category to search for.
     * @return Service[] Returns an array of Service objects.
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
     * Finds services by performing a LIKE search on their title.
     * @param string $query The search query.
     * @return Service[] Returns an array of matching Service objects.
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

    /**
     * Finds all services that a specific volunteer has attended.
     * @param mixed $volunteer The volunteer entity.
     * @return Service[] Returns an array of Service objects.
     */
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

    /**
     * Finds all services scheduled to start on a specific date.
     * @param \DateTimeInterface $date The date to search for.
     * @return Service[] Returns an array of Service objects.
     */
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

    /**
     * Counts the number of services that started in the current month.
     * @return int The total count of services.
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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

    /**
     * Counts the number of services that have already been completed (end date is in the past).
     * @return int The total count of completed services.
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countCompletedServices(): int
    {
        return (int) $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.endDate < :now')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Finds all services that were completed within the current year.
     * @return Service[] Returns an array of completed Service objects for the current year.
     */
    public function findCompletedServicesThisYear(): array
    {
        $startDate = new \DateTime('first day of january this year 00:00:00');

        return $this->createQueryBuilder('s')
            ->select('s')
            ->distinct()
            ->innerJoin('s.volunteerServices', 'vs')
            ->innerJoin('vs.fichajes', 'f')
            ->where('s.endDate < :now')
            ->andWhere('s.startDate >= :start')
            ->setParameter('now', new \DateTime())
            ->setParameter('start', $startDate)
            ->getQuery()
            ->getResult();
    }

    public function findUpcomingServices(int $limit = 5): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.startDate > :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('s.startDate', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findRecentCompletedServices(int $limit = 5): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.endDate < :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('s.endDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds services completed in the last 30 days.
     * @return Service[]
     */
    public function findCompletedServicesLast30Days(): array
    {
        $since = new \DateTime('30 days ago');
        $now = new \DateTime();

        return $this->createQueryBuilder('s')
            ->where('s.endDate >= :since')
            ->andWhere('s.endDate < :now')
            ->setParameter('since', $since)
            ->setParameter('now', $now)
            ->orderBy('s.endDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOpen(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isArchived = :isArchived')
            ->setParameter('isArchived', false)
            ->orderBy('s.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findArchived(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isArchived = :isArchived')
            ->setParameter('isArchived', true)
            ->orderBy('s.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}