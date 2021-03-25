<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait BillingPostalCode
{
    private string $billingPostalCode;

    public function billingPostalCode(): string
    {
        return $this->billingPostalCode;
    }

    /**
     * @return $this
     */
    public function withBillingPostalCode(string $billingPostalCode): self
    {
        $clone = clone $this;

        $clone->billingPostalCode = $billingPostalCode;

        return $clone;
    }
}
