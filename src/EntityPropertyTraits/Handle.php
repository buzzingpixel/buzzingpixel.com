<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Handle
{
    private string $handle;

    public function handle(): string
    {
        return $this->handle;
    }

    /**
     * @return $this
     */
    public function withHandle(string $handle): self
    {
        $clone = clone $this;

        $clone->handle = $handle;

        return $clone;
    }
}
