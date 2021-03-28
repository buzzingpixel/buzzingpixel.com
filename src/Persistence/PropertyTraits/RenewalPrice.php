<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait RenewalPrice
{
    /**
     * @Mapping\Column(
     *     name="renewal_price",
     *     type="float",
     * )
     */
    protected float | int $renewalPrice = 0.0;

    public function getRenewalPrice(): float | int
    {
        return $this->renewalPrice;
    }

    public function setRenewalPrice(float | int $renewalPrice): void
    {
        $this->renewalPrice = $renewalPrice;
    }
}
