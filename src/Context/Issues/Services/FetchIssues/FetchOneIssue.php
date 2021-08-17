<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchIssues;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\FetchIssues;
use App\Persistence\QueryBuilders\Support\IssueQueryBuilder;

class FetchOneIssue
{
    public function __construct(private FetchIssues $fetchIssues)
    {
    }

    public function fetch(IssueQueryBuilder $queryBuilder): ?Issue
    {
        return $this->fetchIssues->fetch(
            queryBuilder: $queryBuilder->withLimit(1),
        )->firstOrNull();
    }
}
