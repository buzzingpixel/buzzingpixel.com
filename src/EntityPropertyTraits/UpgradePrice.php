<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\MoneyFormatter;
use Money\Currency;
use Money\Money;

trait UpgradePrice
{
    private Money $upgradePrice;

    public function upgradePrice(): Money
    {
        return $this->upgradePrice;
    }

    public function upgradePriceAsInt(): int
    {
        return (int) $this->upgradePrice->getAmount();
    }

    public function upgradePriceFormatted(): string
    {
        return MoneyFormatter::format($this->upgradePrice);
    }

    /**
     * @return $this
     */
    public function withUpgradePrice(int | Money $upgradePrice): self
    {
        $clone = clone $this;

        if ($upgradePrice instanceof Money) {
            $clone->upgradePrice = $upgradePrice;
        } else {
            $clone->upgradePrice = new Money(
                $upgradePrice,
                new Currency('USD')
            );
        }

        return $clone;
    }
}
