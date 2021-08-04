<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripeSubscriptionItemId
{
    private string $stripeSubscriptionItemId;

    public function stripeSubscriptionItemId(): string
    {
        return $this->stripeSubscriptionItemId;
    }

    /**
     * @return $this
     */
    public function withStripeSubscriptionItemId(
        string $stripeSubscriptionItemId
    ): self {
        $clone = clone $this;

        $clone->stripeSubscriptionItemId = $stripeSubscriptionItemId;

        return $clone;
    }
}
