<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait BillingStateProvince
{
    private string $billingStateProvince;

    public function billingStateProvince(): string
    {
        return $this->billingStateProvince;
    }

    /**
     * @return $this
     */
    public function withBillingStateProvince(string $billingStateProvince): self
    {
        $clone = clone $this;

        $clone->billingStateProvince = $billingStateProvince;

        return $clone;
    }
}
