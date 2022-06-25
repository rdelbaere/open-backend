<?php

namespace App\Util\Doctrine;

trait PurgeRepositoryTrait
{
    public function purge(): void
    {
        $stmt = $this->createQueryBuilder('e');
        $stmt->delete($this->getClassName());
        $stmt->getQuery()->execute();
    }
}
