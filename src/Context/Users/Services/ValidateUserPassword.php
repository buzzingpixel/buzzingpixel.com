<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\User;

use function password_needs_rehash;
use function password_verify;

use const PASSWORD_DEFAULT;

class ValidateUserPassword
{
    public function __construct(private SaveUser $saveUser)
    {
    }

    /**
     * Returns the UserEntity if password is valid (because potentially
     * passwordHash could be updated so Entity could be new [immutable]) or
     * null if password did not validate
     *
     * @param bool $rehashPasswordIfNeeded Only set false if about to update password
     */
    public function validate(
        User $user,
        string $password,
        bool $rehashPasswordIfNeeded = true,
    ): ?User {
        $hash = $user->passwordHash();

        if (! password_verify($password, $hash)) {
            return null;
        }

        if (! $rehashPasswordIfNeeded) {
            return $user;
        }

        if (! password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            return $user;
        }

        $user = $user->withPassword($password);

        $this->saveUser->save($user);

        return $user;
    }
}
