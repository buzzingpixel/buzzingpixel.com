<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalIssues\Factories;

use App\Persistence\QueryBuilders\Issues\IssueQueryBuilder;

use function assert;

class FetchTotalIssuesQueryBuilderFactory
{
    public function getQueryBuilder(
        ?IssueQueryBuilder $queryBuilder = null,
    ): IssueQueryBuilder {
        if ($queryBuilder === null) {
            $queryBuilder = new IssueQueryBuilder();
        }

        $issueQueryBuilder = $queryBuilder->withClearOrderBy()
            ->withLimit(limit: null)
            ->withOffset(null);

        assert($issueQueryBuilder instanceof IssueQueryBuilder);

        return $issueQueryBuilder;
    }
}
