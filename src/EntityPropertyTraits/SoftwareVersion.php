<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait SoftwareVersion
{
    private string $softwareVersion;

    public function softwareVersion(): string
    {
        return $this->softwareVersion;
    }

    /**
     * @return $this
     */
    public function withSoftwareVersion(string $softwareVersion): self
    {
        $clone = clone $this;

        $clone->softwareVersion = $softwareVersion;

        return $clone;
    }
}
