<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsActive
{
    private bool $isActive = true;

    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return $this
     */
    public function withIsActive(bool $isActive): self
    {
        $clone = clone $this;

        $clone->isActive = $isActive;

        return $clone;
    }
}
