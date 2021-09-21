<?php

declare(strict_types=1);

namespace App\Context\Orders\Services\SearchOrders\Factories;

use App\Context\Orders\Services\SearchOrders\Contracts\SearchOrdersResultBuilderContract;
use App\Context\Orders\Services\SearchOrders\Services\SearchOrdersResultBuilder;
use App\Context\Orders\Services\SearchOrders\Services\SearchOrdersResultBuilderNoResults;

use function count;

class SearchOrderBuilderFactory
{
    public function __construct(
        private SearchOrdersResultBuilder $builder,
        private SearchOrdersResultBuilderNoResults $noResults,
    ) {
    }

    /**
     * @param string[] $resultIds
     */
    public function make(array $resultIds): SearchOrdersResultBuilderContract
    {
        if (count($resultIds) < 1) {
            return $this->noResults;
        }

        return $this->builder;
    }
}
