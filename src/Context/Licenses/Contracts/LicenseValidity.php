<?php

declare(strict_types=1);

namespace App\Context\Licenses\Contracts;

interface LicenseValidity
{
    public function isValid(): bool;

    public function payloadStatusText(): string;

    /**
     * @return string[]
     */
    public function validationErrors(): array;
}
