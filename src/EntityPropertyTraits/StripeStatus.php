<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripeStatus
{
    private string $stripeStatus;

    public function stripeStatus(): string
    {
        return $this->stripeStatus;
    }

    /**
     * @return $this
     */
    public function withStripeStatus(string $stripeStatus): self
    {
        $clone = clone $this;

        $clone->stripeStatus = $stripeStatus;

        return $clone;
    }
}
