<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Solution
{
    private string $solution;

    public function solution(): string
    {
        return $this->solution;
    }

    /**
     * @return $this
     */
    public function withSolution(string $solution): self
    {
        $clone = clone $this;

        $clone->solution = $solution;

        return $clone;
    }
}
