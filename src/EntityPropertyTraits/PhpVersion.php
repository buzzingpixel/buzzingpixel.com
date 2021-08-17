<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait PhpVersion
{
    private string $phpVersion;

    public function phpVersion(): string
    {
        return $this->phpVersion;
    }

    /**
     * @return $this
     */
    public function withPhpVersion(string $phpVersion): self
    {
        $clone = clone $this;

        $clone->phpVersion = $phpVersion;

        return $clone;
    }
}
