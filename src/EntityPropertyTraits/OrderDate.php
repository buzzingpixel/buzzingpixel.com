<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\DateTimeUtility;
use DateTimeImmutable;
use DateTimeInterface;

trait OrderDate
{
    private ?DateTimeImmutable $orderDate = null;

    public function orderDate(): ?DateTimeImmutable
    {
        return $this->orderDate;
    }

    /**
     * @return $this
     */
    public function withOrderDate(
        null | string | DateTimeInterface $orderDate,
    ): self {
        $clone = clone $this;

        $clone->orderDate = DateTimeUtility::createDateTimeImmutableOrNull(
            $orderDate,
        );

        return $clone;
    }
}
