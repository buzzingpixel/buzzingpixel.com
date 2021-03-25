<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait RunEvery
{
    private float | int | string $runEvery;

    public function runEvery(): float | int | string
    {
        return $this->runEvery;
    }

    /**
     * @return $this
     */
    public function withRunEvery(float | int | string $runEvery): self
    {
        $clone = clone $this;

        $clone->runEvery = $runEvery;

        return $clone;
    }
}
