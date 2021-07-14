<?php

declare(strict_types=1);

namespace App\Context\Orders\Services;

use App\Context\Orders\Entities\Order;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;

class FetchOneOrder
{
    public function __construct(private FetchOrders $fetchOrders)
    {
    }

    public function fetch(OrderQueryBuilder $queryBuilder): ?Order
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->fetchOrders->fetch(
            queryBuilder: $queryBuilder->withLimit(1),
        )->firstOrNull();
    }
}
