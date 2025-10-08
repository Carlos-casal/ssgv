<?php

namespace App\Repository;

use App\Entity\Volunteer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for Volunteer entities.
 *
 * @extends ServiceEntityRepository<Volunteer>
 *
 * @method Volunteer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Volunteer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Volunteer[]    findAll()
 * @method Volunteer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolunteerRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry The manager registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Volunteer::class);
    }

    /**
     * Finds volunteers by a search term matching their name or email.
     * @param string $searchTerm The term to search for.
     * @return Volunteer[] Returns an array of matching Volunteer objects.
     */
    public function findBySearchTerm(string $searchTerm): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.name LIKE :searchTerm OR v.email LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('v.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds volunteers by their status.
     * @param string $status The status to filter by.
     * @return Volunteer[] Returns an array of matching Volunteer objects.
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.status = :status')
            ->setParameter('status', $status)
            ->orderBy('v.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Counts the number of volunteers with a 'pending' status.
     * @return int The total count of pending volunteers.
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countPendingVolunteers(): int
    {
        return (int) $this->createQueryBuilder('v')
            ->select('count(v.id)')
            ->andWhere('v.status = :status')
            ->setParameter('status', Volunteer::STATUS_PENDING)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Counts the number of volunteers with an 'active' status.
     * @return int The total count of active volunteers.
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countActiveVolunteers(): int
    {
        return $this->count(['status' => Volunteer::STATUS_ACTIVE]);
    }

    /**
     * Counts the number of new volunteers who joined in the current month.
     * @return int The total count of new volunteers this month.
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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

    public function findRecentVolunteers(int $limit = 5): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.joinDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds volunteers who joined or had a status change in the last 30 days.
     * @return Volunteer[]
     */
    public function findRecentActivityVolunteers(): array
    {
        $since = new \DateTime('30 days ago');

        return $this->createQueryBuilder('v')
            ->where('v.joinDate >= :since OR v.statusChangeDate >= :since')
            ->setParameter('since', $since)
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds all used 'indicativo' numbers from volunteers who are not inactive.
     * This includes active, suspended, and pending volunteers.
     * @return string[]
     */
    public function findUsedIndicativos(): array
    {
        $qb = $this->createQueryBuilder('v')
            ->select('v.indicativo')
            ->where('v.indicativo IS NOT NULL')
            ->andWhere('v.status != :status')
            ->setParameter('status', Volunteer::STATUS_INACTIVE)
            ->orderBy('v.indicativo', 'ASC');

        $result = $qb->getQuery()->getScalarResult();

        return array_map('current', $result);
    }

    /**
     * Finds the highest numeric indicativo value in the table.
     * @return int
     */
    public function findHighestIndicativo(): int
    {
        $qb = $this->createQueryBuilder('v')
            ->select('v.indicativo')
            ->where('v.indicativo IS NOT NULL')
            ->orderBy('LENGTH(v.indicativo)', 'DESC')
            ->addOrderBy('v.indicativo', 'DESC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        // Return 0 if no indicativo is found
        if (!$result || !is_numeric($result['indicativo'])) {
            return 0;
        }

        return (int) $result['indicativo'];
    }
}