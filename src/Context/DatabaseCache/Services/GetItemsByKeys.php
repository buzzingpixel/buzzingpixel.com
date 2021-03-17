<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache\Services;

use App\Context\DatabaseCache\Entities\DatabaseCacheItem;
use App\Context\DatabaseCache\Entities\DatabaseCacheItemCollection;
use App\Persistence\Entities\Cache\CachePoolItemRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;
use function array_values;

class GetItemsByKeys
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    /**
     * @param string[] $keys
     *
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @phpstan-ignore-next-line
     */
    public function get(array $keys = []): DatabaseCacheItemCollection
    {
        try {
            return $this->innerGet($keys);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for database cache items',
                ['exception' => $exception],
            );

            return new DatabaseCacheItemCollection();
        }
    }

    /**
     * @param string[] $keys
     *
     * @phpstan-ignore-next-line
     */
    private function innerGet(array $keys): DatabaseCacheItemCollection
    {
        /** @psalm-suppress MixedArgument */
        $collection = new DatabaseCacheItemCollection(array_map(
            static fn (
                CachePoolItemRecord $r,
            ) => DatabaseCacheItem::fromRecord($r),
            $this->entityManager
                ->getRepository(CachePoolItemRecord::class)
                ->createQueryBuilder('c')
                ->where('c.key IN(:keys)')
                ->setParameter('keys', array_values($keys))
                ->getQuery()
                ->getResult(),
        ));

        foreach ($keys as $key) {
            $count = $collection->where('getKey', $key)
                ->count();

            if ($count > 0) {
                continue;
            }

            $collection->add(new DatabaseCacheItem($key));
        }

        return $collection;
    }
}
