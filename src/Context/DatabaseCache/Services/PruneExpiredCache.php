<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache\Services;

use App\Persistence\Entities\Cache\CachePoolItemRecord;
use App\Utilities\SystemClock;
use Config\General;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;

class PruneExpiredCache
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
        private SystemClock $systemClock,
    ) {
    }

    public function prune(): void
    {
        try {
            $this->innerPrune();
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught pruning expired caches',
                ['exception' => $exception],
            );
        }
    }

    private function innerPrune(): void
    {
        $datetime = $this->systemClock->getCurrentTime()
            ->setTimezone(new DateTimeZone('UTC'));

        $records = $this->entityManager
            ->getRepository(CachePoolItemRecord::class)
            ->createQueryBuilder('c')
            ->where('c.expiresAt < :dateTime')
            ->setParameter('dateTime', $datetime->format(
                DateTimeInterface::ATOM,
            ))
            ->getQuery()
            ->toIterable();

        $batchSize = 20;

        $i = 1;

        $total = 0;

        foreach ($records as $record) {
            assert($record instanceof CachePoolItemRecord);
            $total++;

            $this->entityManager->remove($record);

            if ($i % $batchSize === 0) {
                $this->entityManager->flush(); // Executes all deletions.
                $this->entityManager->clear(); // Detaches all objects from Doctrine
            }

            ++$i;
        }

        $this->entityManager->flush();
    }
}
