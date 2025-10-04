<?php

namespace App\Repository;

use App\Entity\ActivityLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityLog>
 *
 * @method ActivityLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityLog[]    findAll()
 * @method ActivityLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityLog::class);
    }

    public function findRecentActivity(int $limit = 3): array
    {
        $oneMonthAgo = new \DateTimeImmutable('-1 month');

        return $this->createQueryBuilder('a')
            ->where('a.createdAt >= :one_month_ago')
            ->setParameter('one_month_ago', $oneMonthAgo)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}