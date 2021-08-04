<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripeSubscriptionId
{
    private string $stripeSubscriptionId;

    public function stripeSubscriptionId(): string
    {
        return $this->stripeSubscriptionId;
    }

    /**
     * @return $this
     */
    public function withStripeSubscriptionId(string $stripeSubscriptionId): self
    {
        $clone = clone $this;

        $clone->stripeSubscriptionId = $stripeSubscriptionId;

        return $clone;
    }
}
