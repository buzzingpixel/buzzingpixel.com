<?php

declare(strict_types=1);

namespace App\Context\Orders\Events;

use App\Context\Orders\Entities\Order;
use App\Events\StoppableEvent;
use App\Payload\Payload;

class SaveOrderAfterSave extends StoppableEvent
{
    public function __construct(
        public Order $order,
        public Payload $payload,
    ) {
    }
}
