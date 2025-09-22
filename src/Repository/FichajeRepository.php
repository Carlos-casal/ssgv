<?php

namespace App\Repository;

use App\Entity\AssistanceConfirmation;
use App\Entity\Fichaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fichaje>
 *
 * @method Fichaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fichaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fichaje[]    findAll()
 * @method Fichaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fichaje::class);
    }

//    /**
//     * @return Fichaje[] Returns an array of Fichaje objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fichaje
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function calculateTotalDurationInMinutes(AssistanceConfirmation $assistanceConfirmation): int
    {
        $fichajes = $this->findBy(
            ['assistanceConfirmation' => $assistanceConfirmation],
            ['timestamp' => 'ASC']
        );

        $totalDuration = 0;
        $lastIn = null;

        foreach ($fichajes as $fichaje) {
            if ($fichaje->getType() === 'in') {
                $lastIn = $fichaje->getTimestamp();
            } elseif ($fichaje->getType() === 'out' && $lastIn) {
                $duration = $fichaje->getTimestamp()->getTimestamp() - $lastIn->getTimestamp();
                $totalDuration += $duration;
                $lastIn = null;
            }
        }

        if ($lastIn) {
            $service = $assistanceConfirmation->getService();
            if ($service && $service->getEndDate()) {
                $duration = $service->getEndDate()->getTimestamp() - $lastIn->getTimestamp();
                if ($duration > 0) {
                    $totalDuration += $duration;
                }
            }
        }

        return round($totalDuration / 60);
    }
}
