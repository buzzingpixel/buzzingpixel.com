<?php

declare(strict_types=1);

namespace App\EntityValueObjects;

use LogicException;

use function filter_var;

use const FILTER_VALIDATE_EMAIL;

class EmailAddress
{
    public function __construct(private string $emailAddress)
    {
        if (
            filter_var(
                $this->emailAddress,
                FILTER_VALIDATE_EMAIL
            ) !== false
        ) {
            return;
        }

        throw new LogicException(
            '$emailAddress string must be a valid email address'
        );
    }

    public function toString(): string
    {
        return $this->emailAddress;
    }

    public static function fromString(string $emailAddress): self
    {
        return new self($emailAddress);
    }
}
