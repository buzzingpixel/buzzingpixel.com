<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeCreated
{
    /**
     * @Mapping\Column(
     *     name="stripe_created",
     *     type="string",
     * )
     */
    protected string $stripeCreated = '';

    public function getStripeCreated(): string
    {
        return $this->stripeCreated;
    }

    public function setStripeCreated(string $stripeCreated): void
    {
        $this->stripeCreated = $stripeCreated;
    }
}
