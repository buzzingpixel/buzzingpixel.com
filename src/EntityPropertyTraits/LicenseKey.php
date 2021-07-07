<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait LicenseKey
{
    private string $licenseKey;

    public function licenseKey(): string
    {
        return $this->licenseKey;
    }

    /**
     * @return $this
     */
    public function withLicenseKey(string $licenseKey): self
    {
        $clone = clone $this;

        $clone->licenseKey = $licenseKey;

        return $clone;
    }
}
