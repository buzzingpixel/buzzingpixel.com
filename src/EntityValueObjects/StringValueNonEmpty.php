<?php

declare(strict_types=1);

namespace App\EntityValueObjects;

use InvalidArgumentException;
use Stringable;

class StringValueNonEmpty implements Stringable
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException(
                'Must not be empty'
            );
        }
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function hasValue(): bool
    {
        return $this->value !== '';
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
