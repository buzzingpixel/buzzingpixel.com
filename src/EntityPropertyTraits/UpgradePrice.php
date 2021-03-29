<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait UpgradePrice
{
    private float | int $upgradePrice;

    public function upgradePrice(): float | int
    {
        return $this->upgradePrice;
    }

    /**
     * @return $this
     */
    public function withUpgradePrice(float | int $upgradePrice): self
    {
        $clone = clone $this;

        $clone->upgradePrice = $upgradePrice;

        return $clone;
    }
}
