<?php

declare(strict_types=1);

namespace App\Context\Issues\Services;

use App\Context\Issues\Entities\Issue;
use App\Persistence\QueryBuilders\Issues\IssueQueryBuilder;

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
