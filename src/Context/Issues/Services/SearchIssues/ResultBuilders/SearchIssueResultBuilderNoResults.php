<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues\ResultBuilders;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Issues\Services\SearchIssues\Contracts\SearchIssuesResultBuilderContract;
use App\Context\Users\Entities\User;

class SearchIssueResultBuilderNoResults implements SearchIssuesResultBuilderContract
{
    /**
     * @inheritDoc
     */
    public function buildResult(
        array $resultIds,
        FetchParams $fetchParams,
        ?User $user = null,
    ): IssuesResult {
        return new IssuesResult(
            absoluteTotal: 0,
            issueCollection: new IssueCollection(),
        );
    }
}
