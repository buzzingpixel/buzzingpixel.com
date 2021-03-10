<?php

declare(strict_types=1);

namespace App\Context\Queue;

use App\Context\Queue\Entities\QueueEntity;
use App\Context\Queue\Entities\QueueItemEntity;
use App\Context\Queue\Services\AddToQueue;
use App\Context\Queue\Services\FetchNextQueueItem;
use App\Payload\Payload;

class QueueApi
{
    public function __construct(
        private AddToQueue $addToQueue,
        private FetchNextQueueItem $fetchNextQueueItem,
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
}
