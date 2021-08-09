<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeSubscriptionAmount
{
    /**
     * @Mapping\Column(
     *     name="stripe_subscription_amount",
     *     type="integer",
     *     options={"default" : 0},
     * )
     */
    protected int $stripeSubscriptionAmount = 0;

    public function getStripeSubscriptionAmount(): int
    {
        return $this->stripeSubscriptionAmount;
    }

    public function setStripeSubscriptionAmount(
        int $stripeSubscriptionAmount
    ): void {
        $this->stripeSubscriptionAmount = $stripeSubscriptionAmount;
    }
}
