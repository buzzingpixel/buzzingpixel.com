<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\CheckLicenseStatus\Entities;

class CheckLicenseResult
{
    public function __construct(
        private bool $isValid,
        private string $reason = '',
    ) {
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
