<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues\Factories;

use App\Context\Issues\Services\SearchIssues\Contracts\SearchIssuesResultBuilderContract;
use App\Context\Issues\Services\SearchIssues\ResultBuilders\SearchIssueBuilderPublic;
use App\Context\Issues\Services\SearchIssues\ResultBuilders\SearchIssueBuilderPublicPlusUser;
use App\Context\Issues\Services\SearchIssues\ResultBuilders\SearchIssueBuilderUser;
use App\Context\Issues\Services\SearchIssues\ResultBuilders\SearchIssueResultBuilderNoResults;

use function count;

class SearchIssueBuilderFactory
{
    public function __construct(
        private SearchIssueBuilderUser $userBuilder,
        private SearchIssueBuilderPublic $publicBuilder,
        private SearchIssueResultBuilderNoResults $noResultsBuilder,
        private SearchIssueBuilderPublicPlusUser $publicPlusUserBuilder,
    ) {
    }

    /** @param string[] $resultIds */
    public function getSearchIssueBuilder(
        array $resultIds,
        string $mode,
    ): SearchIssuesResultBuilderContract {
        if (count($resultIds) < 1) {
            return $this->noResultsBuilder;
        }

        if ($mode === 'public') {
            return $this->publicBuilder;
        }

        if ($mode === 'publicPlusUser') {
            return $this->publicPlusUserBuilder;
        }

        if ($mode === 'user') {
            return $this->userBuilder;
        }

        return $this->noResultsBuilder;
    }
}
