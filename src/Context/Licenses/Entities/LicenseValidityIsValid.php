<?php

declare(strict_types=1);

namespace App\Context\Licenses\Entities;

use App\Context\Licenses\Contracts\LicenseValidity;
use App\Payload\Payload;

class LicenseValidityIsValid implements LicenseValidity
{
    public function isValid(): bool
    {
        return true;
    }

    public function payloadStatusText(): string
    {
        return Payload::STATUS_VALID;
    }

    /**
     * @inheritDoc
     */
    public function validationErrors(): array
    {
        return [];
    }
}
