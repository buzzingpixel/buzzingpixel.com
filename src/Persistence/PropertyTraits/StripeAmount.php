<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeAmount
{
    /**
     * @Mapping\Column(
     *     name="stripe_amount",
     *     type="string",
     * )
     */
    protected string $stripeAmount = '';

    public function getStripeAmount(): string
    {
        return $this->stripeAmount;
    }

    public function setStripeAmount(string $stripeAmount): void
    {
        $this->stripeAmount = $stripeAmount;
    }
}
