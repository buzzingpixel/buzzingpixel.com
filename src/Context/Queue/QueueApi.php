<?php

declare(strict_types=1);

namespace App\Context\Queue;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueCollection;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\Services\AddToQueue;
use App\Context\Queue\Services\CleanDeadQueues;
use App\Context\Queue\Services\CleanOldQueues;
use App\Context\Queue\Services\FetchIncompleteQueues;
use App\Context\Queue\Services\FetchLastXErrorQueues;
use App\Context\Queue\Services\FetchLastXFinishedQueues;
use App\Context\Queue\Services\FetchNextQueueItem;
use App\Context\Queue\Services\FetchNextXQueues;
use App\Context\Queue\Services\FetchStalledQueues;
use App\Context\Queue\Services\MarkAsStarted;
use App\Context\Queue\Services\MarkQueueItemStoppedDueToError;
use App\Context\Queue\Services\QueueItemPostRun;
use App\Context\Queue\Services\RunQueueItem;
use App\Payload\Payload;
use Throwable;

class QueueApi
{
    public function __construct(
        private AddToQueue $addToQueue,
        private RunQueueItem $runQueueItem,
        private MarkAsStarted $markAsStarted,
        private CleanOldQueues $cleanOldQueues,
        private CleanDeadQueues $cleanDeadQueues,
        private FetchNextXQueues $fetchNextXQueues,
        private QueueItemPostRun $queueItemPostRun,
        private FetchNextQueueItem $fetchNextQueueItem,
        private FetchStalledQueues $fetchStalledQueues,
        private FetchIncompleteQueues $fetchIncompleteQueues,
        private FetchLastXErrorQueues $fetchLastXErrorQueues,
        private FetchLastXFinishedQueues $fetchLastXFinishedQueues,
        private MarkQueueItemStoppedDueToError $markQueueItemStoppedDueToError,
    ) {
    }

    public function addToQueue(Queue $queue): Payload
    {
        return $this->addToQueue->add(queue: $queue);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function fetchNextQueueItem(): ?QueueItem
    {
        return $this->fetchNextQueueItem->fetch();
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function markAsStarted(Queue $queue): Queue
    {
        return $this->markAsStarted->mark(queue: $queue);
    }

    public function runItem(QueueItem $queueItem): void
    {
        $this->runQueueItem->run(queueItem:$queueItem);
    }

    public function queueItemPostRun(QueueItem $queueItem): void
    {
        $this->queueItemPostRun->postRun(queueItem: $queueItem);
    }

    public function markItemStoppedDueToError(
        Queue $queue,
        ?Throwable $e = null,
    ): void {
        $this->markQueueItemStoppedDueToError->mark(queue: $queue, e: $e);
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @phpstan-ignore-next-line
     */
    public function fetchStalledQueues(): QueueCollection
    {
        return $this->fetchStalledQueues->fetch();
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @phpstan-ignore-next-line
     */
    public function fetchIncompleteQueues(): QueueCollection
    {
        return $this->fetchIncompleteQueues->fetch();
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function cleanOldQueues(): int
    {
        return $this->cleanOldQueues->clean();
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function cleanDeadQueues(): int
    {
        return $this->cleanDeadQueues->clean();
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @phpstan-ignore-next-line
     */
    public function fetchNextXQueues(int $maxResults = 10): QueueCollection
    {
        return $this->fetchNextXQueues->fetch(maxResults: $maxResults);
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @phpstan-ignore-next-line
     */
    public function fetchLastXFinishedQueues(
        int $maxResults = 10
    ): QueueCollection {
        return $this->fetchLastXFinishedQueues->fetch(maxResults: $maxResults);
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @phpstan-ignore-next-line
     */
    public function fetchLastXErrorQueues(int $maxResults = 10): QueueCollection
    {
        return $this->fetchLastXErrorQueues->fetch(maxResults: $maxResults);
    }
}
