<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Search\Factories;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Issues\IssuesApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Search\Entities\SearchIssuesResult;

use function count;

class SearchIssuesResultFactory
{
    public function __construct(private IssuesApi $issuesApi)
    {
    }

    /**
     * @param string[] $statusFilter
     */
    public function getSearchResults(
        string $query,
        LoggedInUser $loggedInUser,
        int $pageNumber,
        int $perPage,
        array $statusFilter,
    ): SearchIssuesResult {
        if ($query === '' && count($statusFilter) < 1) {
            return new SearchIssuesResult(
                isValid: false,
                issuesResult: new IssuesResult(
                    absoluteTotal: 0,
                    issueCollection: new IssueCollection(),
                ),
            );
        }

        $fetchParams = new FetchParams(
            limit: $perPage,
            offset: ($pageNumber * $perPage) - $perPage,
            statusFilter: $statusFilter,
        );

        if ($loggedInUser->hasUser()) {
            return new SearchIssuesResult(
                isValid: true,
                issuesResult: $this->issuesApi->searchPublicPlusUsersIssues(
                    searchString: $query,
                    user: $loggedInUser->user(),
                    fetchParams: $fetchParams,
                ),
            );
        }

        return new SearchIssuesResult(
            isValid: true,
            issuesResult: $this->issuesApi->searchPublicIssues(
                searchString: $query,
                fetchParams: $fetchParams,
            ),
        );
    }
}
