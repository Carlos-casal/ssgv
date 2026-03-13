<?php

namespace App\Repository;

use App\Entity\KitTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KitTemplate>
 *
 * @method KitTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method KitTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method KitTemplate[]    findAll()
 * @method KitTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KitTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KitTemplate::class);
    }
}
