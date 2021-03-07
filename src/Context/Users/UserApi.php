<?php

declare(strict_types=1);

namespace App\Context\Users;

use App\Context\Users\Entities\UserCollection;
use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Services\FetchUsers;
use App\Context\Users\Services\SaveUser;
use App\Context\Users\Services\ValidateUserPassword;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;

class UserApi
{
    public function __construct(
        private SaveUser $saveUser,
        private FetchUsers $fetchUsers,
        private ValidateUserPassword $validateUserPassword,
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

    public function fetchOneUser(UserQueryBuilder $queryBuilder): ?UserEntity
    {
        $userCollection = $this->fetchUsers(
            $queryBuilder->withLimit(1)
        );

        if ($userCollection->count() < 1) {
            return null;
        }

        return $userCollection->first();
    }

    /**
     * Returns the UserEntity if password is valid (because potentially
     * passwordHash could be updated so Entity could be new [immutable]) or
     * null if password did not validate
     *
     * @param bool $rehashPasswordIfNeeded Only set false if about to update password
     */
    public function validateUserPassword(
        UserEntity $user,
        string $password,
        bool $rehashPasswordIfNeeded = true,
    ): ?UserEntity {
        return $this->validateUserPassword->validate(
            $user,
            $password,
            $rehashPasswordIfNeeded
        );
    }
}
