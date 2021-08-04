<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeSubscriptionId
{
    /**
     * @Mapping\Column(
     *     name="stripe_subscription_id",
     *     type="string",
     *     options={"default" : ""},
     * )
     */
    protected string $stripeSubscriptionId = '';

    public function getStripeSubscriptionId(): string
    {
        return $this->stripeSubscriptionId;
    }

    public function setStripeSubscriptionId(string $stripeSubscriptionId): void
    {
        $this->stripeSubscriptionId = $stripeSubscriptionId;
    }
}
