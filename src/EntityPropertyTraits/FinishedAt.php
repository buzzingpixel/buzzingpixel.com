<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\DateTimeUtility;
use DateTimeImmutable;
use DateTimeInterface;

trait FinishedAt
{
    private ?DateTimeImmutable $finishedAt = null;

    public function finishedAt(): ?DateTimeImmutable
    {
        return $this->finishedAt;
    }

    /**
     * @return $this
     */
    public function withFinishedAt(
        null | string | DateTimeInterface $finishedAt,
    ): self {
        $clone = clone $this;

        $clone->finishedAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $finishedAt,
        );

        return $clone;
    }
}
