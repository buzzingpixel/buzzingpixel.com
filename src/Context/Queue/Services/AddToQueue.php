<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\QueueEntity;
use App\Payload\Payload;

class AddToQueue
{
    public function __construct(private SaveQueue $saveQueue)
    {
    }

    public function add(QueueEntity $queue): Payload
    {
        return $this->saveQueue->save($queue);
    }
}
