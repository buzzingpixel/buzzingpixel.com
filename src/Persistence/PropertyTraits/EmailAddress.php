<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait EmailAddress
{
    /**
     * @Mapping\Column(
     *     name="email_address",
     *     type="string",
     * )
     */
    protected string $emailAddress = '';

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }
}
