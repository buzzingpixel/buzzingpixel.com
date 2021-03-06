<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait PasswordHash
{
    /**
     * @Mapping\Column(
     *     name="password_hash",
     *     type="string",
     * )
     */
    protected string $passwordHash = '';

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }
}
