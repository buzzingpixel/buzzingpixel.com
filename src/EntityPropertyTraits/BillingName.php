<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait BillingName
{
    private string $billingName;

    public function billingName(): string
    {
        return $this->billingName;
    }

    /**
     * @return $this
     */
    public function withBillingName(string $billingName): self
    {
        $clone = clone $this;

        $clone->billingName = $billingName;

        return $clone;
    }
}
