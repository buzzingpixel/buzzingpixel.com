<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripeAmount
{
    private string $stripeAmount;

    public function stripeAmount(): string
    {
        return $this->stripeAmount;
    }

    /**
     * @return $this
     */
    public function withStripeAmount(string $stripeAmount): self
    {
        $clone = clone $this;

        $clone->stripeAmount = $stripeAmount;

        return $clone;
    }
}
