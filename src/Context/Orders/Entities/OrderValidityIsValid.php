<?php

declare(strict_types=1);

namespace App\Context\Orders\Entities;

use App\Context\Orders\Contracts\OrderValidity;
use App\Payload\Payload;

class OrderValidityIsValid implements OrderValidity
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
