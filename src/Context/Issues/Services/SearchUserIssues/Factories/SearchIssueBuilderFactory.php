<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchUserIssues\Factories;

use App\Context\Issues\Services\SearchUserIssues\Contracts\SearchUserIssuesResultBuilderContract;
use App\Context\Issues\Services\SearchUserIssues\ResultBuilders\SearchIssueBuilder;
use App\Context\Issues\Services\SearchUserIssues\ResultBuilders\SearchIssueResultBuilderNoResults;

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
    ): SearchUserIssuesResultBuilderContract {
        if (count($resultIds) < 1) {
            return $this->builderNoResults;
        }

        return $this->builder;
    }
}
