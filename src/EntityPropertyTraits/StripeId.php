<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripeId
{
    private string $stripeId;

    public function stripeId(): string
    {
        return $this->stripeId;
    }

    /**
     * @return $this
     */
    public function withStripeId(string $stripeId): self
    {
        $clone = clone $this;

        $clone->stripeId = $stripeId;

        return $clone;
    }
}
