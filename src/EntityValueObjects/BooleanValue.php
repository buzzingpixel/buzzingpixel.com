<?php

declare(strict_types=1);

namespace App\EntityValueObjects;

use Stringable;

class BooleanValue implements Stringable
{
    public function __construct(private bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->value ? '1' : '0';
    }

    public function fromString(string $value): self
    {
        return new self((bool) $value);
    }
}
