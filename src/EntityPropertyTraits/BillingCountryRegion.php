<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait BillingCountryRegion
{
    private string $billingCountryRegion;

    public function billingCountryRegion(): string
    {
        return $this->billingCountryRegion;
    }

    /**
     * @return $this
     */
    public function withBillingCountryRegion(string $billingCountryRegion): self
    {
        $clone = clone $this;

        $clone->billingCountryRegion = $billingCountryRegion;

        return $clone;
    }
}
