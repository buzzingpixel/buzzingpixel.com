<?php

declare(strict_types=1);

namespace App\Context\Orders\Services\SearchOrders\Services;

use App\Context\Orders\Entities\OrderCollection;
use App\Context\Orders\Entities\OrderResult;
use App\Context\Orders\Entities\SearchParams;
use App\Context\Orders\Services\SearchOrders\Contracts\SearchOrdersResultBuilderContract;

class SearchOrdersResultBuilderNoResults implements SearchOrdersResultBuilderContract
{
    /**
     * @inheritDoc
     */
    public function buildResult(
        array $resultIds,
        SearchParams $searchParams,
    ): OrderResult {
        return new OrderResult(
            absoluteTotal: 0,
            orders: new OrderCollection(),
        );
    }
}
