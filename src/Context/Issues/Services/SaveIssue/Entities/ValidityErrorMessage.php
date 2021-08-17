<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Entities;

class ValidityErrorMessage
{
    public function __construct(
        private string $property,
        private string $message,
    ) {
    }

    public function property(): string
    {
        return $this->property;
    }

    public function message(): string
    {
        return $this->message;
    }
}
