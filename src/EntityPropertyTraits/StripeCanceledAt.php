<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\DateTimeUtility;
use DateTimeImmutable;
use DateTimeInterface;

trait StripeCanceledAt
{
    private ?DateTimeImmutable $stripeCanceledAt = null;

    public function stripeCanceledAt(): ?DateTimeImmutable
    {
        return $this->stripeCanceledAt;
    }

    /**
     * @return $this
     */
    public function withStripeCanceledAt(
        null | string | DateTimeInterface $stripeCanceledAt,
    ): self {
        $clone = clone $this;

        $clone->stripeCanceledAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $stripeCanceledAt,
        );

        return $clone;
    }
}
