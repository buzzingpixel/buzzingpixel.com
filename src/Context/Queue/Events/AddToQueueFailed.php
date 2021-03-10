<?php

declare(strict_types=1);

namespace App\Context\Queue\Events;

use App\Context\Queue\Entities\QueueEntity;
use Throwable;

class AddToQueueFailed
{
    public function __construct(
        public QueueEntity $userEntity,
        public Throwable $exception,
    ) {
    }
}
