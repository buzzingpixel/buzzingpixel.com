<?php

declare(strict_types=1);

namespace App\Context\Issues;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Services\FetchIssues;
use App\Context\Issues\Services\FetchOneIssue;
use App\Context\Issues\Services\FetchTotalIssues;
use App\Context\Issues\Services\SaveIssue;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Support\IssueQueryBuilder;

class IssuesApi
{
    public function __construct(
        private SaveIssue $saveIssue,
        private FetchIssues $fetchIssues,
        private FetchOneIssue $fetchOneIssue,
        private FetchTotalIssues $fetchTotalIssues,
    ) {
    }

    public function saveIssue(Issue $issue): Payload
    {
        return $this->saveIssue->save(issue: $issue);
    }

    /** @phpstan-ignore-next-line */
    public function fetchIssues(
        IssueQueryBuilder $queryBuilder
    ): IssueCollection {
        return $this->fetchIssues->fetch(queryBuilder: $queryBuilder);
    }

    public function fetchOneIssue(IssueQueryBuilder $queryBuilder): ?Issue
    {
        return $this->fetchOneIssue->fetch(queryBuilder: $queryBuilder);
    }

    public function fetchTotalIssues(
        ?IssueQueryBuilder $queryBuilder = null
    ): int {
        return $this->fetchTotalIssues->fetch(queryBuilder: $queryBuilder);
    }
}
