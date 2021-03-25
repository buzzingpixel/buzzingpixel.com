<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsRunning
{
    private bool $isRunning;

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    /**
     * @return $this
     */
    public function withIsRunning(bool $isRunning = true): self
    {
        $clone = clone $this;

        $clone->isRunning = $isRunning;

        return $clone;
    }
}
