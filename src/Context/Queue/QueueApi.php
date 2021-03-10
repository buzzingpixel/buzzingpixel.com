<?php

declare(strict_types=1);

namespace App\Context\Queue;

use App\Context\Queue\Entities\QueueEntity;
use App\Context\Queue\Services\AddToQueue;
use App\Payload\Payload;

class QueueApi
{
    public function __construct(
        private AddToQueue $addToQueue,
    ) {
    }

    public function addToQueue(QueueEntity $queue): Payload
    {
        return $this->addToQueue->add($queue);
    }
}
