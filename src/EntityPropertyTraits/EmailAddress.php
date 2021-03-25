<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\EntityValueObjects\EmailAddress as EmailAddressValue;

trait EmailAddress
{
    private EmailAddressValue $emailAddress;

    public function emailAddress(): string
    {
        return $this->emailAddress->toString();
    }

    /**
     * @return $this
     *
     * @throws InvalidEmailAddress
     */
    public function withEmailAddress(string $emailAddress): self
    {
        $clone = clone $this;

        $clone->emailAddress = EmailAddressValue::fromString(
            $emailAddress
        );

        return $clone;
    }
}
