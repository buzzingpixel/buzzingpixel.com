<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Version
{
    private string $version;

    public function version(): string
    {
        return $this->version;
    }

    /**
     * @return $this
     */
    public function withVersion(string $version): self
    {
        $clone = clone $this;

        $clone->version = $version;

        return $clone;
    }
}
