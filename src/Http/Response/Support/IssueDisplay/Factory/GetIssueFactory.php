<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueDisplay\Factory;

use App\Context\Issues\IssuesApi;
use App\Context\Users\Entities\User;
use App\Http\Response\Support\IssueDisplay\Entities\GetIssueResults;
use App\Persistence\QueryBuilders\Support\IssueQueryBuilder;

class GetIssueFactory
{
    public function __construct(private IssuesApi $issuesApi)
    {
    }

    public function getIssue(
        int $issueNumber,
        ?User $user,
    ): GetIssueResults {
        $issue = $this->issuesApi->fetchOneIssue(
            queryBuilder: (new IssueQueryBuilder())
                ->withIssueNumber($issueNumber),
        );

        if ($issue === null) {
            return new GetIssueResults(
                issue: null,
            );
        }

        if ($issue->isNotEnabled()) {
            if ($user === null || $user->isNotAdmin()) {
                return new GetIssueResults(
                    issue: null,
                );
            }

            return new GetIssueResults(
                issue: $issue,
            );
        }

        if ($issue->isPublic()) {
            return new GetIssueResults(
                issue: $issue,
            );
        }

        if ($user === null || $issue->userGuarantee()->id() !== $user->id()) {
            return new GetIssueResults(
                issue: null,
            );
        }

        return new GetIssueResults(
            issue: $issue,
        );
    }
}
