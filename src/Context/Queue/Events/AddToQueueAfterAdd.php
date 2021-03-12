<?php

declare(strict_types=1);

namespace App\Context\Queue\Events;

use App\Context\Queue\Entities\Queue;
use App\Payload\Payload;

class AddToQueueAfterAdd
{
    public function __construct(
        public Queue $queueEntity,
        public Payload $payload
    ) {
    }
}
