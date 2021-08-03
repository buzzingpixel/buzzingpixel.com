<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeStatus
{
    /**
     * @Mapping\Column(
     *     name="stripe_status",
     *     type="string",
     *     options={"default" : ""},
     * )
     */
    protected string $stripeStatus = '';

    public function getStripeStatus(): string
    {
        return $this->stripeStatus;
    }

    public function setStripeStatus(string $stripeStatus): void
    {
        $this->stripeStatus = $stripeStatus;
    }
}
