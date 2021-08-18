<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsAdmin
{
    private bool $isAdmin = false;

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function isNotAdmin(): bool
    {
        return ! $this->isAdmin();
    }

    /**
     * @return $this
     */
    public function withIsAdmin(bool $isAdmin): self
    {
        $clone = clone $this;

        $clone->isAdmin = $isAdmin;

        return $clone;
    }
}
