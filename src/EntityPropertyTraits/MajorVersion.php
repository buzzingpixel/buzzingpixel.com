<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait MajorVersion
{
    private string $majorVersion;

    public function majorVersion(): string
    {
        return $this->majorVersion;
    }

    /**
     * @return $this
     */
    public function withMajorVersion(string $majorVersion): self
    {
        $clone = clone $this;

        $clone->majorVersion = $majorVersion;

        return $clone;
    }
}
