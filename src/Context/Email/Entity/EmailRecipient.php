<?php

declare(strict_types=1);

namespace App\Context\Email\Entity;

use LogicException;

use function filter_var;

use const FILTER_VALIDATE_EMAIL;

class EmailRecipient
{
    public function __construct(
        private string $emailAddress,
        private ?string $name = null,
    ) {
        $check = filter_var(
            $this->emailAddress,
            FILTER_VALIDATE_EMAIL
        );

        if ($check !== false) {
            return;
        }

        throw new LogicException('A valid email address is required');
    }

    public function emailAddress(): string
    {
        return $this->emailAddress;
    }

    public function name(): ?string
    {
        return $this->name;
    }
}
