<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserEntity;

use function password_hash;
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
        UserEntity $user,
        string $password,
        bool $rehashPasswordIfNeeded = true,
    ): ?UserEntity {
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

        $user = $user->withPasswordHash(
            /** @phpstan-ignore-next-line */
            (string) password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
        );

        $this->saveUser->save($user);

        return $user;
    }
}
