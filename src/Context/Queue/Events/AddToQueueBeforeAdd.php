<?php

declare(strict_types=1);

namespace App\Context\Queue\Events;

use App\Context\Queue\Entities\Queue;

class AddToQueueBeforeAdd
{
    public function __construct(public Queue $queueEntity)
    {
    }
}
