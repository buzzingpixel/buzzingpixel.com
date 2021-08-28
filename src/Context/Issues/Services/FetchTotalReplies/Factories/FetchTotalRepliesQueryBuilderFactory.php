<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalReplies\Factories;

use App\Persistence\QueryBuilders\Issues\IssueMessageQueryBuilder;

use function assert;

class FetchTotalRepliesQueryBuilderFactory
{
    public function getQueryBuilder(
        ?IssueMessageQueryBuilder $queryBuilder = null,
    ): IssueMessageQueryBuilder {
        if ($queryBuilder === null) {
            $queryBuilder = new IssueMessageQueryBuilder();
        }

        $issueMessageQueryBuilder = $queryBuilder->withClearOrderBy()
            ->withLimit(limit: null)
            ->withOffset(offset: null);

        assert(
            $issueMessageQueryBuilder instanceof IssueMessageQueryBuilder
        );

        return $issueMessageQueryBuilder;
    }
}
