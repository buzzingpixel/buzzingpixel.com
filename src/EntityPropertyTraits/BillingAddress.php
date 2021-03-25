<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait BillingAddress
{
    private string $billingAddress;

    public function billingAddress(): string
    {
        return $this->billingAddress;
    }

    /**
     * @return $this
     */
    public function withBillingAddress(string $billingAddress): self
    {
        $clone = clone $this;

        $clone->billingAddress = $billingAddress;

        return $clone;
    }
}
