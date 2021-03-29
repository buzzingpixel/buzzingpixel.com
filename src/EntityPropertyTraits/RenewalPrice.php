<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait RenewalPrice
{
    private float | int $renewalPrice;

    public function renewalPrice(): float | int
    {
        return $this->renewalPrice;
    }

    /**
     * @return $this
     */
    public function withRenewalPrice(float | int $renewalPrice): self
    {
        $clone = clone $this;

        $clone->renewalPrice = $renewalPrice;

        return $clone;
    }
}
