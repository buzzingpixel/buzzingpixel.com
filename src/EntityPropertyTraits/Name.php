<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Name
{
    private string $name;

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function withName(string $name): self
    {
        $clone = clone $this;

        $clone->name = $name;

        return $clone;
    }
}
