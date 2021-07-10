<?php

declare(strict_types=1);

namespace App\Context\Orders\Entities;

use App\Context\Orders\Contracts\OrderValidity;
use App\Payload\Payload;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingTraversableTypeHintSpecification

class OrderValidityIsInvalid implements OrderValidity
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
