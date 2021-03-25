<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait BillingCompany
{
    private string $billingCompany;

    public function billingCompany(): string
    {
        return $this->billingCompany;
    }

    /**
     * @return $this
     */
    public function withBillingCompany(string $billingCompany): self
    {
        $clone = clone $this;

        $clone->billingCompany = $billingCompany;

        return $clone;
    }
}
