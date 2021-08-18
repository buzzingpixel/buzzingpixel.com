<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

class LoggedInUser
{
    private User $user;

    public function __construct(?User $user)
    {
        if ($user === null) {
            return;
        }

        $this->user = $user;
    }

    public function hasUser(): bool
    {
        return isset($this->user);
    }

    public function hasNoUser(): bool
    {
        return ! $this->hasUser();
    }

    public function user(): User
    {
        return $this->user;
    }

    public function userOrNull(): ?User
    {
        return $this->hasUser() ? $this->user() : null;
    }
}
