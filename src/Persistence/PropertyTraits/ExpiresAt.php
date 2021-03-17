<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait ExpiresAt
{
    /**
     * @Mapping\Column(
     *     name="expires_at",
     *     type="datetimetz_immutable",
     *     nullable=true
     * )
     */
    protected ?DateTimeImmutable $expiresAt;

    public function getExpiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTimeImmutable $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }
}
