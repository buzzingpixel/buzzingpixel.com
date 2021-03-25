<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait BillingPhone
{
    private string $billingPhone;

    public function billingPhone(): string
    {
        return $this->billingPhone;
    }

    /**
     * @return $this
     */
    public function withBillingPhone(string $billingPhone): self
    {
        $clone = clone $this;

        $clone->billingPhone = $billingPhone;

        return $clone;
    }
}
