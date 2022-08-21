<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

class UserResult
{
    /** @phpstan-ignore-next-line */
    public function __construct(
        private int $absoluteTotal,
        /** @phpstan-ignore-next-line */
        private UserCollection $users,
    ) {
    }

    public function absoluteTotal(): int
    {
        return $this->absoluteTotal;
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function users(): UserCollection
    {
        return $this->users;
    }
}
