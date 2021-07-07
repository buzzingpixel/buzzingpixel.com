<?php

declare(strict_types=1);

namespace App\Context\Licenses\Entities;

use App\Context\Licenses\Contracts\LicenseValidity;
use App\Payload\Payload;

class LicenseValidityIsInvalid implements LicenseValidity
{
    /**
     * @param string[] $validationErrors
     */
    public function __construct(
        private array $validationErrors
    ) {
    }

    public function isValid(): bool
    {
        return false;
    }

    public function payloadStatusText(): string
    {
        return Payload::STATUS_NOT_VALID;
    }

    /**
     * @inheritDoc
     */
    public function validationErrors(): array
    {
        return $this->validationErrors;
    }
}
