<?php

namespace App\Repository;

use App\Entity\App;
use App\Util\Doctrine\EntityUtilRepositoryTrait;
use App\Util\Doctrine\PurgeRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppRepository extends ServiceEntityRepository
{
    use EntityUtilRepositoryTrait;
    use PurgeRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, App::class);
    }
}
