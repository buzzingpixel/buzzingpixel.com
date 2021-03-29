<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Price
{
    private float | int $price;

    public function price(): float | int
    {
        return $this->price;
    }

    /**
     * @return $this
     */
    public function withPrice(float | int $price): self
    {
        $clone = clone $this;

        $clone->price = $price;

        return $clone;
    }
}
