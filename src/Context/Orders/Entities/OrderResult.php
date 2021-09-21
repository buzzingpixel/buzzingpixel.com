<?php

declare(strict_types=1);

namespace App\Context\Orders\Entities;

class OrderResult
{
    /** @phpstan-ignore-next-line */
    public function __construct(
        private int $absoluteTotal,
        /** @phpstan-ignore-next-line */
        private OrderCollection $orders
    ) {
    }

    public function absoluteTotal(): int
    {
        return $this->absoluteTotal;
    }

    /** @phpstan-ignore-next-line */
    public function orders(): OrderCollection
    {
        return $this->orders;
    }
}
