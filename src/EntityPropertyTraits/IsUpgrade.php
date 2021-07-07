<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsUpgrade
{
    private bool $isUpgrade;

    public function isUpgrade(): bool
    {
        return $this->isUpgrade;
    }

    /**
     * @return $this
     */
    public function withIsUpgrade(bool $isUpgrade = true): self
    {
        $clone = clone $this;

        $clone->isUpgrade = $isUpgrade;

        return $clone;
    }
}
