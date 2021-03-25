<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait HasStarted
{
    private bool $hasStarted;

    public function hasStarted(): bool
    {
        return $this->hasStarted;
    }

    /**
     * @return $this
     */
    public function withHasStarted(bool $hasStarted): self
    {
        $clone = clone $this;

        $clone->hasStarted = $hasStarted;

        return $clone;
    }
}
