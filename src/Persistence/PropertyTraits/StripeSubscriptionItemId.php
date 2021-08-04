<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeSubscriptionItemId
{
    /**
     * @Mapping\Column(
     *     name="stripe_subscription_item_id",
     *     type="string",
     *     options={"default" : ""},
     * )
     */
    protected string $stripeSubscriptionItemId = '';

    public function getStripeSubscriptionItemId(): string
    {
        return $this->stripeSubscriptionItemId;
    }

    public function setStripeSubscriptionItemId(
        string $stripeSubscriptionItemId
    ): void {
        $this->stripeSubscriptionItemId = $stripeSubscriptionItemId;
    }
}
