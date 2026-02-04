<?php

namespace App\Repository;

use App\Entity\VolunteerUniform;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VolunteerUniformRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VolunteerUniform::class);
    }
}
