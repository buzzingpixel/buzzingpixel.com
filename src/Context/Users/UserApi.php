<?php

declare(strict_types=1);

namespace App\Context\Users;

use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserCollection;
use App\Context\Users\Entities\UserPasswordResetToken;
use App\Context\Users\Services\DeleteUser;
use App\Context\Users\Services\FetchLoggedInUser;
use App\Context\Users\Services\FetchOneUser;
use App\Context\Users\Services\FetchTotalUserResetTokens;
use App\Context\Users\Services\FetchTotalUsers;
use App\Context\Users\Services\FetchUserByResetToken;
use App\Context\Users\Services\FetchUsers;
use App\Context\Users\Services\GeneratePasswordResetToken;
use App\Context\Users\Services\LogCurrentUserOut;
use App\Context\Users\Services\LogUserIn;
use App\Context\Users\Services\RequestPasswordResetEmail;
use App\Context\Users\Services\ResetPasswordByToken;
use App\Context\Users\Services\SaveUser;
use App\Context\Users\Services\ValidateUserPassword;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;

class UserApi
{
    public function __construct(
        private SaveUser $saveUser,
        private FetchTotalUsers $fetchTotalUsers,
        private FetchUsers $fetchUsers,
        private FetchOneUser $fetchOneUser,
        private ValidateUserPassword $validateUserPassword,
        private LogUserIn $logUserIn,
        private DeleteUser $deleteUser,
        private FetchLoggedInUser $fetchLoggedInUser,
        private GeneratePasswordResetToken $generatePasswordResetToken,
        private RequestPasswordResetEmail $requestPasswordResetEmail,
        private FetchTotalUserResetTokens $fetchTotalUserResetTokens,
        private FetchUserByResetToken $fetchUserByResetToken,
        private LogCurrentUserOut $logCurrentUserOut,
        private ResetPasswordByToken $resetPasswordByToken,
    ) {
    }

    public function saveUser(User $user): Payload
    {
        return $this->saveUser->save($user);
    }

    public function fetchTotalUsers(?UserQueryBuilder $queryBuilder = null): int
    {
        return $this->fetchTotalUsers->fetch($queryBuilder);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function fetchUsers(UserQueryBuilder $queryBuilder): UserCollection
    {
        return $this->fetchUsers->fetch($queryBuilder);
    }

    public function fetchOneUser(UserQueryBuilder $queryBuilder): ?User
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
        User $user,
        string $password,
        bool $rehashPasswordIfNeeded = true,
    ): ?User {
        return $this->validateUserPassword->validate(
            $user,
            $password,
            $rehashPasswordIfNeeded
        );
    }

    public function logUserIn(User $user, string $password): Payload
    {
        return $this->logUserIn->logUserIn($user, $password);
    }

    public function deleteUser(User $user): Payload
    {
        return $this->deleteUser->delete($user);
    }

    public function fetchLoggedInUser(): ?User
    {
        return $this->fetchLoggedInUser->fetch();
    }

    public function generatePasswordResetToken(User $user): ?UserPasswordResetToken
    {
        return $this->generatePasswordResetToken->generate($user);
    }

    public function requestPasswordResetEmail(User $user): void
    {
        $this->requestPasswordResetEmail->request($user);
    }

    public function fetchTotalUserResetTokens(User $user): int
    {
        return $this->fetchTotalUserResetTokens->fetch($user);
    }

    public function fetchUserByResetToken(string $token): ?User
    {
        return $this->fetchUserByResetToken->fetch($token);
    }

    public function logCurrentUserOut(): Payload
    {
        return $this->logCurrentUserOut->logOut();
    }

    public function resetPasswordByToken(
        string $token,
        string $newPassword
    ): Payload {
        return $this->resetPasswordByToken->reset(
            $token,
            $newPassword
        );
    }
}
