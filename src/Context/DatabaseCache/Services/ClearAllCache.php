<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache\Services;

use App\Persistence\Entities\Cache\CachePoolItemRecord;
use Doctrine\ORM\EntityManager;

class ClearAllCache
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    public function clear(): void
    {
        $this->entityManager
            ->createQuery(
                'delete from ' . CachePoolItemRecord::class
            )
            ->execute();
    }
}
