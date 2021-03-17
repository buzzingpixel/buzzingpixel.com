<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache\Services;

use App\Persistence\Entities\Cache\CachePoolItemRecord;
use Doctrine\ORM\EntityManager;

use function array_values;
use function count;

class DeleteItemsByKeys
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    /**
     * @param string[] $keys
     */
    public function delete(array $keys): void
    {
        if (count($keys) < 1) {
            return;
        }

        $this->entityManager->createQueryBuilder()
            ->delete(CachePoolItemRecord::class, 'c')
            ->where('c.key IN(:keys)')
            ->setParameter('keys', array_values($keys))
            ->getQuery()
            ->execute();
    }
}
