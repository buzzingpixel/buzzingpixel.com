<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\SearchLicenses\Factories;

use App\Context\Licenses\Services\SearchLicenses\Contracts\SearchLicensesResultBuilderContract;
use App\Context\Licenses\Services\SearchLicenses\Services\SearchLicensesResultBuilder;
use App\Context\Licenses\Services\SearchLicenses\Services\SearchLicensesResultBuilderNoResults;

use function count;

class SearchLicensesResultBuilderFactory
{
    public function __construct(
        private SearchLicensesResultBuilder $builder,
        private SearchLicensesResultBuilderNoResults $noResults,
    ) {
    }

    /**
     * @param string[] $resultIds
     */
    public function make(array $resultIds): SearchLicensesResultBuilderContract
    {
        if (count($resultIds) < 1) {
            return $this->noResults;
        }

        return $this->builder;
    }
}
