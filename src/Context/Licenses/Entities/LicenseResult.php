<?php

declare(strict_types=1);

namespace App\Context\Licenses\Entities;

class LicenseResult
{
    /** @phpstan-ignore-next-line */
    public function __construct(
        private int $absoluteTotal,
        /** @phpstan-ignore-next-line */
        private LicenseCollection $licenses
    ) {
    }

    public function absoluteTotal(): int
    {
        return $this->absoluteTotal;
    }

    /** @phpstan-ignore-next-line */
    public function licenses(): LicenseCollection
    {
        return $this->licenses;
    }
}
