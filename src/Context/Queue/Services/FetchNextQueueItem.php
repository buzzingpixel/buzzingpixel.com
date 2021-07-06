<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Persistence\Entities\Queue\QueueRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;

class FetchNextQueueItem
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
        private QueueItemPostRun $itemPostRun,
    ) {
    }

    public function fetch(): ?QueueItem
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

    private function innerFetch(): ?QueueItem
    {
        $record = $this->entityManager
            ->getRepository(QueueRecord::class)
            ->createQueryBuilder('q')
            ->where('q.isRunning = false')
            ->where('q.isFinished = false')
            ->setMaxResults(1)
            ->orderBy('q.addedAt', 'asc')
            ->getQuery()
            ->getOneOrNullResult();

        if ($record === null) {
            return null;
        }

        assert($record instanceof QueueRecord);

        $entity = Queue::fromRecord($record);

        foreach ($entity->queueItems() as $queueItem) {
            assert($queueItem instanceof QueueItem);

            if ($queueItem->isFinished()) {
                continue;
            }

            return $queueItem;
        }

        if (isset($queueItem)) {
            /** @psalm-suppress MixedArgument */
            $this->itemPostRun->postRun($queueItem);
        }

        return null;
    }
}
