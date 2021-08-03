<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\DateTimeUtility;
use DateTimeImmutable;
use DateTimeInterface;

trait ExpiresAt
{
    private ?DateTimeImmutable $expiresAt = null;

    public function expiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    /**
     * @return $this
     */
    public function withExpiresAt(
        null | string | DateTimeInterface $expiresAt,
    ): self {
        $clone = clone $this;

        $clone->expiresAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $expiresAt,
        );

        return $clone;
    }
}
