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
     * Finds all services that were completed within the current year,
     * with eager loading of volunteer services and clock-in records to avoid N+1 queries.
     * @return Service[] Returns an array of completed Service objects for the current year.
     */
    public function findCompletedServicesThisYear(): array
    {
        $startDate = new \DateTime('first day of january this year 00:00:00');

        return $this->createQueryBuilder('s')
            ->select('s', 'vs', 'f')
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

    /**
     * Gets the next sequential number for services in a given year.
     * @param int $year The year to check.
     * @return int The next sequential number.
     */
    public function getNextSequentialNumber(int $year): int
    {
        $startOfYear = new \DateTime("$year-01-01 00:00:00");
        $endOfYear = new \DateTime("$year-12-31 23:59:59");

        $count = (int) $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.startDate BETWEEN :start AND :end')
            ->setParameter('start', $startOfYear)
            ->setParameter('end', $endOfYear)
            ->getQuery()
            ->getSingleScalarResult();

        return $count + 1;
    }

    /**
     * Gets the next sequential number for services of a specific type in a given year.
     */
    public function getNextSequentialNumberByTypeAndYear($type, int $year): int
    {
        $startOfYear = new \DateTime("$year-01-01 00:00:00");
        $endOfYear = new \DateTime("$year-12-31 23:59:59");

        $qb = $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.startDate BETWEEN :start AND :end')
            ->setParameter('start', $startOfYear)
            ->setParameter('end', $endOfYear);

        if ($type) {
            $qb->andWhere('s.type = :type')
               ->setParameter('type', $type);
        } else {
            $qb->andWhere('s.type IS NULL');
        }

        return (int) $qb->getQuery()->getSingleScalarResult() + 1;
    }

    /**
     * Generates the next suggested numeration ID for a service.
     */
    public function generateNextNumeration($type, ?\DateTimeInterface $startDate): string
    {
        $year = $startDate ? (int)$startDate->format('Y') : (int)date('Y');

        $typeCode = 'SERV';
        if ($type instanceof \App\Entity\ServiceType) {
            $typeCode = $type->getCode() ?: mb_strtoupper(mb_substr($type->getName(), 0, 3));
        }

        $sequentialNumber = $this->getNextSequentialNumberByTypeAndYear($type, $year);

        return sprintf('%s-%d/%03d', $typeCode, $year, $sequentialNumber);
    }

    /**
     * Gets the absolute total count of services in the system.
     */
    public function getGlobalTotalCount(): int
    {
        return (int) $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Gets the sequential number for services within a specific Type/Category/Subcategory classification.
     */
    public function getClassSequentialNumber($type, $category, $subcategory): int
    {
        $qb = $this->createQueryBuilder('s')
            ->select('count(s.id)');

        if ($type) {
            $qb->andWhere('s.type = :type')->setParameter('type', $type);
        }
        if ($category) {
            $qb->andWhere('s.category = :category')->setParameter('category', $category);
        }
        if ($subcategory) {
            $qb->andWhere('s.subcategory = :subcategory')->setParameter('subcategory', $subcategory);
        }

        return (int) $qb->getQuery()->getSingleScalarResult() + 1;
    }

    /**
     * Calculates the total service minutes for a given period using DQL.
     * This avoids loading all service and fichaje entities into memory.
     *
     * Note: This method calculates the sum of all individual fichaje durations.
     * If multiple volunteers are in the same service, their times are added up.
     */
    public function calculateTotalServiceMinutes(\DateTimeInterface $start, \DateTimeInterface $end): int
    {
        // For broad compatibility (SQLite/MySQL/PostgreSQL) without custom DQL functions,
        // we use a Native Query.
        $sql = "
            SELECT SUM(
                COALESCE(
                    strftime('%s', f.end_time) - strftime('%s', f.start_time),
                    strftime('%s', s.end_date) - strftime('%s', f.start_time)
                )
            ) as total_seconds
            FROM fichaje f
            JOIN volunteer_service vs ON f.volunteer_service_id = vs.id
            JOIN service s ON vs.service_id = s.id
            WHERE s.start_date >= :start
              AND s.end_date < :end
              AND s.end_date < :now
        ";

        // Check if we are in PostgreSQL (often used in prod)
        $conn = $this->getEntityManager()->getConnection();
        if ($conn->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQLPlatform) {
            $sql = "
                SELECT SUM(
                    EXTRACT(EPOCH FROM (COALESCE(f.end_time, s.end_date) - f.start_time))
                ) as total_seconds
                FROM fichaje f
                JOIN volunteer_service vs ON f.volunteer_service_id = vs.id
                JOIN service s ON vs.service_id = s.id
                WHERE s.start_date >= :start
                  AND s.end_date < :end
                  AND s.end_date < :now
            ";
        } elseif ($conn->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQLPlatform) {
             $sql = "
                SELECT SUM(
                    TIMESTAMPDIFF(SECOND, f.start_time, COALESCE(f.end_time, s.end_date))
                ) as total_seconds
                FROM fichaje f
                JOIN volunteer_service vs ON f.volunteer_service_id = vs.id
                JOIN service s ON vs.service_id = s.id
                WHERE s.start_date >= :start
                  AND s.end_date < :end
                  AND s.end_date < :now
            ";
        }

        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('total_seconds', 'total_seconds');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('start', $start->format('Y-m-d H:i:s'));
        $query->setParameter('end', $end->format('Y-m-d H:i:s'));
        $query->setParameter('now', (new \DateTime())->format('Y-m-d H:i:s'));

        $result = $query->getSingleScalarResult();

        return (int) round(($result ?? 0) / 60);
    }
}