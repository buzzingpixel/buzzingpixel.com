<?php

declare(strict_types=1);

namespace App\Context\Users\Services\SearchUsers\Factories;

use App\Context\Users\Services\SearchUsers\Contracts\SearchUsersResultBuilderContract;
use App\Context\Users\Services\SearchUsers\Services\SearchUsersResultBuilder;
use App\Context\Users\Services\SearchUsers\Services\SearchUsersResultBuilderNoResults;

use function count;

class SearchUsersResultBuilderFactory
{
    public function __construct(
        private SearchUsersResultBuilder $builder,
        private SearchUsersResultBuilderNoResults $noResults,
    ) {
    }

    /**
     * @param string[] $resultIds
     */
    public function make(array $resultIds): SearchUsersResultBuilderContract
    {
        if (count($resultIds) < 1) {
            return $this->noResults;
        }

        return $this->builder;
    }
}
