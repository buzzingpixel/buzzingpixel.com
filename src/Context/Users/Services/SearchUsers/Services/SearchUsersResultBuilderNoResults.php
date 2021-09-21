<?php

declare(strict_types=1);

namespace App\Context\Users\Services\SearchUsers\Services;

use App\Context\Users\Entities\SearchParams;
use App\Context\Users\Entities\UserCollection;
use App\Context\Users\Entities\UserResult;
use App\Context\Users\Services\SearchUsers\Contracts\SearchUsersResultBuilderContract;

class SearchUsersResultBuilderNoResults implements SearchUsersResultBuilderContract
{
    /**
     * @inheritDoc
     */
    public function buildResult(
        array $resultIds,
        SearchParams $searchParams,
    ): UserResult {
        return new UserResult(
            absoluteTotal: 0,
            users: new UserCollection(),
        );
    }
}
