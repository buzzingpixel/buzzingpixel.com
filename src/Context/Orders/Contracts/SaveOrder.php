<?php

declare(strict_types=1);

namespace App\Context\Orders\Contracts;

use App\Context\Orders\Entities\Order;
use App\Payload\Payload;
use App\Persistence\Entities\Orders\OrderRecord;

interface SaveOrder
{
    public function save(
        Order $order,
        ?OrderRecord $orderRecord = null,
    ): Payload;
}
