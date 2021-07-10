<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripeCreated
{
    private string $stripeCreated;

    public function stripeCreated(): string
    {
        return $this->stripeCreated;
    }

    /**
     * @return $this
     */
    public function withStripeCreated(
        string $stripeCreated
    ): self {
        $clone = clone $this;

        $clone->stripeCreated = $stripeCreated;

        return $clone;
    }
}
