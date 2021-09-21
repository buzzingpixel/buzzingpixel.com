<?php

declare(strict_types=1);

namespace App\Context\Users\Services\SearchUsers\Contracts;

use App\Context\Users\Entities\SearchParams;
use App\Context\Users\Entities\UserResult;

interface SearchUsersResultBuilderContract
{
    /**
     * @param string[] $resultIds
     */
    public function buildResult(
        array $resultIds,
        SearchParams $searchParams,
    ): UserResult;
}
