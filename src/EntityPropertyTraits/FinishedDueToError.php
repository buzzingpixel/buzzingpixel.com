<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait FinishedDueToError
{
    private bool $finishedDueToError;

    public function finishedDueToError(): bool
    {
        return $this->finishedDueToError;
    }

    /**
     * @return $this
     */
    public function withFinishedDueToError(bool $finishedDueToError = true): self
    {
        $clone = clone $this;

        $clone->finishedDueToError = $finishedDueToError;

        return $clone;
    }
}
