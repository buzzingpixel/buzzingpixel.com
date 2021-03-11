<?php

declare(strict_types=1);

namespace App\Context\Queue;

use App\Context\Queue\Entities\QueueEntity;
use App\Context\Queue\Entities\QueueItemEntity;
use App\Context\Queue\Services\AddToQueue;
use App\Context\Queue\Services\FetchNextQueueItem;
use App\Context\Queue\Services\MarkAsStarted;
use App\Context\Queue\Services\QueueItemPostRun;
use App\Context\Queue\Services\RunQueueItem;
use App\Payload\Payload;

class QueueApi
{
    public function __construct(
        private AddToQueue $addToQueue,
        private FetchNextQueueItem $fetchNextQueueItem,
        private MarkAsStarted $markAsStarted,
        private RunQueueItem $runQueueItem,
        private QueueItemPostRun $queueItemPostRun,
    ) {
    }

    public function addToQueue(QueueEntity $queue): Payload
    {
        return $this->addToQueue->add($queue);
    }

    public function fetchNextQueueItem(): ?QueueItemEntity
    {
        return $this->fetchNextQueueItem->fetch();
    }

    public function markAsStarted(QueueEntity $queue): QueueEntity
    {
        return $this->markAsStarted->mark($queue);
    }

    public function runItem(QueueItemEntity $queueItem): void
    {
        $this->runQueueItem->run($queueItem);
    }

    public function queueItemPostRun(QueueItemEntity $queueItem): void
    {
        $this->queueItemPostRun->postRun($queueItem);
    }
}
