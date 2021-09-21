<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexOrder;

use App\Context\ElasticSearch\Services\IndexOrder\Factories\IndexOrderFactory;
use App\Context\Orders\Entities\Order;

class IndexOrder
{
    public function __construct(private IndexOrderFactory $indexOrderFactory)
    {
    }

    public function indexOrder(Order $order): void
    {
        $this->indexOrderFactory->make(order: $order)
            ->indexOrder(order: $order);
    }
}
