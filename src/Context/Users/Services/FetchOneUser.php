<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserEntity;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;

class FetchOneUser
{
    public function __construct(private FetchUsers $fetchUsers)
    {
    }

    public function fetch(UserQueryBuilder $queryBuilder): ?UserEntity
    {
        $userCollection = $this->fetchUsers->fetch(
            $queryBuilder->withLimit(1)
        );

        if ($userCollection->count() < 1) {
            return null;
        }

        return $userCollection->first();
    }
}
