<?php

declare(strict_types=1);

namespace App\Http\Entities\ValueObjects;

use Stringable;

class StringValue implements Stringable
{
    public function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function hasValue(): bool
    {
        return $this->value !== '';
    }
}
