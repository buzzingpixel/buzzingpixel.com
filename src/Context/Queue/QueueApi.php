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
use App\Context\Queue\Services\FetchNextQueueItem;
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
        private FetchNextQueueItem $fetchNextQueueItem,
        private MarkAsStarted $markAsStarted,
        private RunQueueItem $runQueueItem,
        private QueueItemPostRun $queueItemPostRun,
        private MarkQueueItemStoppedDueToError $markQueueItemStoppedDueToError,
        private FetchStalledQueues $fetchStalledQueues,
        private FetchIncompleteQueues $fetchIncompleteQueues,
        private CleanOldQueues $cleanOldQueues,
        private CleanDeadQueues $cleanDeadQueues,
    ) {
    }

    public function addToQueue(Queue $queue): Payload
    {
        return $this->addToQueue->add($queue);
    }

    public function fetchNextQueueItem(): ?QueueItem
    {
        return $this->fetchNextQueueItem->fetch();
    }

    public function markAsStarted(Queue $queue): Queue
    {
        return $this->markAsStarted->mark($queue);
    }

    public function runItem(QueueItem $queueItem): void
    {
        $this->runQueueItem->run($queueItem);
    }

    public function queueItemPostRun(QueueItem $queueItem): void
    {
        $this->queueItemPostRun->postRun($queueItem);
    }

    public function markItemStoppedDueToError(
        Queue $queue,
        ?Throwable $e = null,
    ): void {
        $this->markQueueItemStoppedDueToError->mark($queue, $e);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function fetchStalledQueues(): QueueCollection
    {
        return $this->fetchStalledQueues->fetch();
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function fetchIncompleteQueues(): QueueCollection
    {
        return $this->fetchIncompleteQueues->fetch();
    }

    public function cleanOldQueues(): int
    {
        return $this->cleanOldQueues->clean();
    }

    public function cleanDeadQueues(): int
    {
        return $this->cleanDeadQueues->clean();
    }
}
