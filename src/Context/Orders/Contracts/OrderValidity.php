<?php

declare(strict_types=1);

namespace App\Context\Orders\Contracts;

interface OrderValidity
{
    public function isValid(): bool;

    public function payloadStatusText(): string;

    /**
     * @return string[]
     */
    public function validationErrors(): array;
}
