<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait UpgradePrice
{
    /**
     * @Mapping\Column(
     *     name="upgrade_price",
     *     type="float",
     * )
     */
    protected float | int $upgradePrice = 0.0;

    public function getUpgradePrice(): float | int
    {
        return $this->upgradePrice;
    }

    public function setPrice(float | int $upgradePrice): void
    {
        $this->upgradePrice = $upgradePrice;
    }
}
