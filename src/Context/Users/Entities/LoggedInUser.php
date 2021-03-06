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

    public function user(): User
    {
        return $this->user;
    }
}
