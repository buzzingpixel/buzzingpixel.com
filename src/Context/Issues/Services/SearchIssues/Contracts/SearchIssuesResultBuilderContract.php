<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues\Contracts;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Users\Entities\User;

interface SearchIssuesResultBuilderContract
{
    /**
     * @param string[] $resultIds
     */
    public function buildResult(
        array $resultIds,
        FetchParams $fetchParams,
        ?User $user = null,
    ): IssuesResult;
}
