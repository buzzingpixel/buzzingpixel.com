<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Quantity
{
    private int $quantity;

    public function quantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return $this
     */
    public function withQuantity(int $quantity): self
    {
        $clone = clone $this;

        $clone->quantity = $quantity;

        return $clone;
    }
}
