<?php

declare(strict_types=1);

namespace App\Context\Orders;

use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderCollection;
use App\Context\Orders\Entities\OrderResult;
use App\Context\Orders\Entities\SearchParams;
use App\Context\Orders\Services\FetchOneOrder;
use App\Context\Orders\Services\FetchOrders;
use App\Context\Orders\Services\FetchTotalOrders;
use App\Context\Orders\Services\SaveOrder;
use App\Context\Orders\Services\SearchOrders\SearchOrders;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;

class OrderApi
{
    public function __construct(
        private SaveOrder $saveOrder,
        private FetchOrders $fetchOrders,
        private FetchOneOrder $fetchOneOrder,
        private FetchTotalOrders $fetchTotalOrders,
        private SearchOrders $searchOrders,
    ) {
    }

    public function saveOrder(Order $order): Payload
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->saveOrder->save($order);
    }

    public function fetchTotalOrders(
        ?OrderQueryBuilder $queryBuilder = null
    ): int {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->fetchTotalOrders->fetch($queryBuilder);
    }

    /** @phpstan-ignore-next-line */
    public function fetchOrders(
        OrderQueryBuilder $queryBuilder
    ): OrderCollection {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->fetchOrders->fetch($queryBuilder);
    }

    public function fetchOneOrder(
        OrderQueryBuilder $queryBuilder
    ): ?Order {
        return $this->fetchOneOrder->fetch($queryBuilder);
    }

    public function searchOrders(SearchParams $searchParams): OrderResult
    {
        return $this->searchOrders->search(searchParams: $searchParams);
    }
}
