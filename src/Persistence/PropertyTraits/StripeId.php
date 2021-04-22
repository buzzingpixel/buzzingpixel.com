<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeId
{
    /**
     * @Mapping\Column(
     *     name="stripe_id",
     *     type="string",
     * )
     */
    protected string $stripeId = '';

    public function getStripeId(): string
    {
        return $this->stripeId;
    }

    public function setStripeId(string $stripeId): void
    {
        $this->stripeId = $stripeId;
    }
}
