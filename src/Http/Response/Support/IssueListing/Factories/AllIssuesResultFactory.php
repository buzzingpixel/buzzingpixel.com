<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing\Factories;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Issues\IssuesApi;
use App\Context\Users\Entities\LoggedInUser;

class AllIssuesResultFactory
{
    public function __construct(private IssuesApi $issuesApi)
    {
    }

    public function getIssueResult(
        LoggedInUser $loggedInUser,
        int $pageNumber,
        int $perPage,
    ): IssuesResult {
        $fetchParams = new FetchParams(
            limit: $perPage,
            offset: ($pageNumber * $perPage) - $perPage,
        );

        if ($loggedInUser->hasUser()) {
            return $this->issuesApi->fetchPublicPlusUsersPrivateIssues(
                user: $loggedInUser->user(),
                fetchParams: $fetchParams,
            );
        }

        return $this->issuesApi->fetchPublicIssues(fetchParams: $fetchParams);
    }
}
