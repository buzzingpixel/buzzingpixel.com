<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Context\Users\Entities\User as UserEntity;

trait User
{
    private UserEntity $user;

    public function user(): UserEntity
    {
        return $this->user;
    }

    /**
     * @return $this
     */
    public function withUser(UserEntity $user): self
    {
        $clone = clone $this;

        $clone->user = $user;

        return $clone;
    }
}
