<?php

namespace App\Repository;

use App\Entity\System;
use App\Util\Doctrine\EntityUtilRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SystemRepository extends ServiceEntityRepository
{
    use EntityUtilRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, System::class);
    }


}
