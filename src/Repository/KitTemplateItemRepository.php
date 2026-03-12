<?php

namespace App\Repository;

use App\Entity\KitTemplateItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KitTemplateItem>
 *
 * @method KitTemplateItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method KitTemplateItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method KitTemplateItem[]    findAll()
 * @method KitTemplateItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KitTemplateItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KitTemplateItem::class);
    }
}
