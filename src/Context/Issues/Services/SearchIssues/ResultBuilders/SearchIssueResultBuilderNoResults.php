<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues\ResultBuilders;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Issues\Services\SearchIssues\Contracts\SearchUserIssuesResultBuilderContract;

class SearchIssueResultBuilderNoResults implements SearchUserIssuesResultBuilderContract
{
    /**
     * @inheritDoc
     */
    public function buildResult(
        array $resultIds,
        FetchParams $fetchParams,
    ): IssuesResult {
        return new IssuesResult(
            absoluteTotal: 0,
            issueCollection: new IssueCollection(),
        );
    }
}
