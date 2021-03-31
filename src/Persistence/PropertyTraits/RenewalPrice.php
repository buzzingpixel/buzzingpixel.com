<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait RenewalPrice
{
    /**
     * @Mapping\Column(
     *     name="renewal_price",
     *     type="integer",
     * )
     */
    protected int $renewalPrice = 0;

    public function getRenewalPrice(): int
    {
        return $this->renewalPrice;
    }

    public function setRenewalPrice(int $renewalPrice): void
    {
        $this->renewalPrice = $renewalPrice;
    }
}
