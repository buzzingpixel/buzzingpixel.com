<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait UpgradePrice
{
    /**
     * @Mapping\Column(
     *     name="upgrade_price",
     *     type="integer",
     * )
     */
    protected int $upgradePrice = 0;

    public function getUpgradePrice(): int
    {
        return $this->upgradePrice;
    }

    public function setUpgradePrice(int $upgradePrice): void
    {
        $this->upgradePrice = $upgradePrice;
    }
}
