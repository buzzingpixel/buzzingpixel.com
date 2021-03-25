<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsFinished
{
    private bool $isFinished;

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    /**
     * @return $this
     */
    public function withIsFinished(bool $isFinished = true): self
    {
        $clone = clone $this;

        $clone->isFinished = $isFinished;

        return $clone;
    }
}
