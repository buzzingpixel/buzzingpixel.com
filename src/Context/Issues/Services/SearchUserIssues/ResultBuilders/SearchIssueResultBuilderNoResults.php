<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchUserIssues\ResultBuilders;

use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Services\SearchUserIssues\Contracts\SearchUserIssuesResultBuilderContract;

class SearchIssueResultBuilderNoResults implements SearchUserIssuesResultBuilderContract
{
    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function buildResult(array $resultIds): IssueCollection
    {
        return new IssueCollection();
    }
}
