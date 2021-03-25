<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait PercentComplete
{
    private float | int $percentComplete;

    public function percentComplete(): float | int
    {
        return $this->percentComplete;
    }

    /**
     * @return $this
     */
    public function withPercentComplete(float | int $percentComplete): self
    {
        $clone = clone $this;

        $clone->percentComplete = $percentComplete;

        return $clone;
    }
}
