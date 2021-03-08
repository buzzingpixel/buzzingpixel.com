<?php

declare(strict_types=1);

namespace App\Context\Users;

use App\Context\Users\Entities\UserCollection;
use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Entities\UserPasswordResetTokenEntity;
use App\Context\Users\Services\DeleteUser;
use App\Context\Users\Services\FetchLoggedInUser;
use App\Context\Users\Services\FetchOneUser;
use App\Context\Users\Services\FetchUsers;
use App\Context\Users\Services\GeneratePasswordResetToken;
use App\Context\Users\Services\LogUserIn;
use App\Context\Users\Services\SaveUser;
use App\Context\Users\Services\ValidateUserPassword;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;

class UserApi
{
    public function __construct(
        private SaveUser $saveUser,
        private FetchUsers $fetchUsers,
        private FetchOneUser $fetchOneUser,
        private ValidateUserPassword $validateUserPassword,
        private LogUserIn $logUserIn,
        private DeleteUser $deleteUser,
        private FetchLoggedInUser $fetchLoggedInUser,
        private GeneratePasswordResetToken $generatePasswordResetToken,
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
        return $this->fetchOneUser->fetch($queryBuilder);
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

    public function logUserIn(UserEntity $user, string $password): Payload
    {
        return $this->logUserIn->logUserIn($user, $password);
    }

    public function deleteUser(UserEntity $user): Payload
    {
        return $this->deleteUser->delete($user);
    }

    public function fetchLoggedInUser(): ?UserEntity
    {
        return $this->fetchLoggedInUser->fetch();
    }

    public function generatePasswordResetToken(UserEntity $user): ?UserPasswordResetTokenEntity
    {
        return $this->generatePasswordResetToken->generate($user);
    }
}
