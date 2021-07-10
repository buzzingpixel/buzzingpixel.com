<?php

declare(strict_types=1);

namespace App\Context\Orders\Factories;

use App\Context\Orders\Contracts\SaveOrder;
use App\Context\Orders\Services\SaveOrderExisting;
use App\Context\Orders\Services\SaveOrderNew;
use App\Persistence\Entities\Orders\OrderRecord;

class SaveOrderFactory
{
    public function __construct(
        private SaveOrderNew $saveOrderNew,
        private SaveOrderExisting $saveOrderExisting,
    ) {
    }

    public function createSaveOrder(
        ?OrderRecord $orderRecord = null,
    ): SaveOrder {
        if ($orderRecord === null) {
            return $this->saveOrderNew;
        }

        return $this->saveOrderExisting;
    }
}
