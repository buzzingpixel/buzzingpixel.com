<?php

declare(strict_types=1);

namespace App\Context\Orders\Services;

use App\Context\Orders\Contracts\SaveOrder;
use App\Context\Orders\Entities\Order;
use App\Payload\Payload;
use App\Persistence\Entities\Orders\OrderRecord;

use function dd;

class SaveOrderExisting implements SaveOrder
{
    public function save(
        Order $order,
        ?OrderRecord $orderRecord = null
    ): Payload {
        dd('SaveOrderExisting');
    }
}
