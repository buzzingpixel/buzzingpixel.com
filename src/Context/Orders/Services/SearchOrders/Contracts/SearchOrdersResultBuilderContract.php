<?php

declare(strict_types=1);

namespace App\Context\Orders\Services\SearchOrders\Contracts;

use App\Context\Orders\Entities\OrderResult;
use App\Context\Orders\Entities\SearchParams;

interface SearchOrdersResultBuilderContract
{
    /**
     * @param string[] $resultIds
     */
    public function buildResult(
        array $resultIds,
        SearchParams $searchParams,
    ): OrderResult;
}
