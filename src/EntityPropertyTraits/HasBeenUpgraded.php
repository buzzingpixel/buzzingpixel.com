<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait HasBeenUpgraded
{
    private bool $hasBeenUpgraded;

    public function hasBeenUpgraded(): bool
    {
        return $this->hasBeenUpgraded;
    }

    /**
     * @return $this
     */
    public function withHasBeenUpgraded(bool $hasBeenUpgraded): self
    {
        $clone = clone $this;

        $clone->hasBeenUpgraded = $hasBeenUpgraded;

        return $clone;
    }
}
