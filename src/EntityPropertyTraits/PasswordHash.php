<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Context\Users\Exceptions\InvalidPassword;

use function mb_strlen;
use function password_hash;
use function preg_match;

use const PASSWORD_DEFAULT;

trait PasswordHash
{
    private string $passwordHash = '';

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return $this
     */
    public function withPasswordHash(string $passwordHash): self
    {
        $clone = clone $this;

        $clone->passwordHash = $passwordHash;

        return $clone;
    }

    /**
     * @throws InvalidPassword
     */
    protected function validatePassword(string $password): void
    {
        $uppercase    = preg_match('@[A-Z]@', $password);
        $lowercase    = preg_match('@[a-z]@', $password);
        $number       = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (
            $uppercase > 0 &&
            $lowercase > 0 &&
            $number > 0 &&
            $specialChars > 0 &&
            mb_strlen($password) > 7
        ) {
            return;
        }

        throw new InvalidPassword();
    }

    /**
     * @return $this
     *
     * @throws InvalidPassword
     */
    public function withPassword(string $password): self
    {
        $this->validatePassword($password);

        /** @phpstan-ignore-next-line */
        return $this->withPasswordHash((string) password_hash(
            $password,
            PASSWORD_DEFAULT,
        ));
    }
}
