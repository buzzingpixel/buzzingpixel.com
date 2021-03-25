<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait BillingAddressContinued
{
    private string $billingAddressContinued;

    public function billingAddressContinued(): string
    {
        return $this->billingAddressContinued;
    }

    /**
     * @return $this
     */
    public function withBillingAddressContinued(
        string $billingAddressContinued
    ): self {
        $clone = clone $this;

        $clone->billingAddressContinued = $billingAddressContinued;

        return $clone;
    }
}
