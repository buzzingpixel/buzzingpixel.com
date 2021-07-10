<?php

declare(strict_types=1);

namespace App\Context\Orders\Events;

use App\Context\Orders\Entities\Order;
use App\Events\StoppableEvent;
use Throwable;

class SaveOrderFailed extends StoppableEvent
{
    public function __construct(
        public Order $order,
        public Throwable $exception,
    ) {
    }
}
