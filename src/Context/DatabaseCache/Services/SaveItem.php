<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache\Services;

use App\Context\DatabaseCache\Entities\DatabaseCacheItem;
use App\Payload\Payload;
use App\Persistence\Entities\Cache\CachePoolItemRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Cache\CacheItemInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;

class SaveItem
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function save(CacheItemInterface $cacheItem): Payload
    {
        try {
            return $this->innerSave($cacheItem);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught saving a database cache item',
                ['exception' => $exception],
            );

            return new Payload(
                Payload::STATUS_ERROR,
                ['message' => $exception->getMessage()],
            );
        }
    }

    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function innerSave(CacheItemInterface $cacheItem): Payload
    {
        assert($cacheItem instanceof DatabaseCacheItem);

        $payloadStatus = Payload::STATUS_UPDATED;

        $this->logger->info(
            'Checking for existing cache item by kye: ' .
                $cacheItem->getKey()
        );

        /** @psalm-suppress MixedAssignment */
        $record = $this->entityManager
            ->getRepository(CachePoolItemRecord::class)
            ->createQueryBuilder('c')
            ->where('c.key = :key')
            ->setParameter('key', $cacheItem->getKey())
            ->getQuery()
            ->getOneOrNullResult();

        if ($record === null) {
            $payloadStatus = Payload::STATUS_CREATED;

            $this->logger->info(
                'The cache item does not exist by key. ' .
                    'Creating new record',
            );

            $record = new CachePoolItemRecord();
        } else {
            $this->logger->info(
                'This cache item was found in the database. ' .
                    'Updating existing cache item',
            );

            assert($record instanceof CachePoolItemRecord);

            if ($cacheItem->id() !== $record->getId()->toString()) {
                $cacheItem = $cacheItem->withId($record->getId());
            }
        }

        $record->hydrateFromEntity($cacheItem);

        $this->entityManager->persist($record);

        $this->entityManager->flush();

        $this->logger->info(
            'The cache item was saved',
        );

        return new Payload($payloadStatus);
    }
}
