<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalIssues\Factories;

use App\Persistence\QueryBuilders\Support\IssueQueryBuilder;

class FetchTotalIssuesQueryBuilderFactory
{
    public function getQueryBuilder(
        ?IssueQueryBuilder $queryBuilder = null
    ): IssueQueryBuilder {
        if ($queryBuilder === null) {
            $queryBuilder = new IssueQueryBuilder();
        }

        return $queryBuilder->withClearOrderBy()
            ->withLimit(null)
            ->withOffset(null);
    }
}
