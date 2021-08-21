<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchUserIssues\Contracts;

use App\Context\Issues\Entities\IssueCollection;

interface SearchUserIssuesResultBuilderContract
{
    /**
     * @param string[] $resultIds
     *
     * @phpstan-ignore-next-line
     */
    public function buildResult(array $resultIds): IssueCollection;
}
