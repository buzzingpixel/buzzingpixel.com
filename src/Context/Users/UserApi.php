<?php

declare(strict_types=1);

namespace App\Context\Users;

use App\Context\Users\Entities\UserCollection;
use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Services\FetchUsers;
use App\Context\Users\Services\SaveUser;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;

class UserApi
{
    public function __construct(
        private SaveUser $saveUser,
        private FetchUsers $fetchUsers,
    ) {
    }

    public function saveUser(UserEntity $user): Payload
    {
        return $this->saveUser->save($user);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function fetchUsers(UserQueryBuilder $queryBuilder): UserCollection
    {
        return $this->fetchUsers->fetch($queryBuilder);
    }
}
