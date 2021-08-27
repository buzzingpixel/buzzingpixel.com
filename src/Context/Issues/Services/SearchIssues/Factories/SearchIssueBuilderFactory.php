<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues\Factories;

use App\Context\Issues\Services\SearchIssues\Contracts\SearchIssuesResultBuilderContract;
use App\Context\Issues\Services\SearchIssues\ResultBuilders\SearchIssueBuilder;
use App\Context\Issues\Services\SearchIssues\ResultBuilders\SearchIssueResultBuilderNoResults;

use function count;

class SearchIssueBuilderFactory
{
    public function __construct(
        private SearchIssueBuilder $builder,
        private SearchIssueResultBuilderNoResults $builderNoResults,
    ) {
    }

    /** @param string[] $resultIds */
    public function getSearchIssueBuilder(
        array $resultIds
    ): SearchIssuesResultBuilderContract {
        if (count($resultIds) < 1) {
            return $this->builderNoResults;
        }

        return $this->builder;
    }
}