<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItemEntity;
use App\Persistence\Entities\Queue\QueueRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;
use function dd;

class FetchNextQueueItem
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function fetch(): ?QueueItemEntity
    {
        try {
            return $this->innerFetch();
        } catch (Throwable $e) {
            if ($this->config->devMode()) {
                throw $e;
            }

            $this->logger->emergency(
                'An exception was caught querying for next queue item',
                ['exception' => $e],
            );

            return null;
        }
    }

    private function innerFetch(): ?QueueItemEntity
    {
        $record = $this->entityManager
            ->getRepository(QueueRecord::class)
            ->createQueryBuilder('q')
            ->where('q.isRunning = false')
            ->where('q.isFinished = false')
            ->orderBy('q.addedAt', 'asc')
            ->getQuery()
            ->getOneOrNullResult();

        if ($record === null) {
            return null;
        }

        assert($record instanceof QueueRecord);

        $entity = Queue::fromRecord($record);

        foreach ($entity->queueItems() as $queueItem) {
            assert($queueItem instanceof QueueItemEntity);

            if ($queueItem->isFinished()) {
                continue;
            }

            return $queueItem;
        }

        // TODO: run postRun
        dd('TODO: Run postRun');
    }
}
