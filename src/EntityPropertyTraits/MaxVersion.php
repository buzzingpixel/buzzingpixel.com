<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait MaxVersion
{
    private string $maxVersion;

    public function maxVersion(): string
    {
        return $this->maxVersion;
    }

    /**
     * @return $this
     */
    public function withMaxVersion(string $maxVersion): self
    {
        $clone = clone $this;

        $clone->maxVersion = $maxVersion;

        return $clone;
    }
}
