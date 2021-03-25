<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait BillingCity
{
    private string $billingCity;

    public function billingCity(): string
    {
        return $this->billingCity;
    }

    /**
     * @return $this
     */
    public function withBillingCity(string $billingCity): self
    {
        $clone = clone $this;

        $clone->billingCity = $billingCity;

        return $clone;
    }
}
