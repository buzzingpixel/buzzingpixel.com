<?php

declare(strict_types=1);

namespace App\Context\Queue\Events;

use App\Context\Queue\Entities\Queue;
use Throwable;

class AddToQueueFailed
{
    public function __construct(
        public Queue $userEntity,
        public Throwable $exception,
    ) {
    }
}
