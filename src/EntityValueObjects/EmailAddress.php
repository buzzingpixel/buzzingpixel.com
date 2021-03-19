<?php

declare(strict_types=1);

namespace App\EntityValueObjects;

use App\Context\Users\Exceptions\InvalidEmailAddress;

use function filter_var;

use const FILTER_VALIDATE_EMAIL;

class EmailAddress
{
    /**
     * @throws InvalidEmailAddress
     */
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

        throw new InvalidEmailAddress();
    }

    public function toString(): string
    {
        return $this->emailAddress;
    }

    /**
     * @throws InvalidEmailAddress
     */
    public static function fromString(string $emailAddress): self
    {
        return new self($emailAddress);
    }
}
