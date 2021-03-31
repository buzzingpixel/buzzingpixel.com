<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\MoneyFormatter;
use Money\Currency;
use Money\Money;

trait RenewalPrice
{
    private Money $renewalPrice;

    public function renewalPrice(): Money
    {
        return $this->renewalPrice;
    }

    public function renewalPriceAsInt(): int
    {
        return (int) $this->renewalPrice->getAmount();
    }

    public function renewalPriceFormatted(): string
    {
        return MoneyFormatter::format($this->renewalPrice);
    }

    /**
     * @return $this
     */
    public function withRenewalPrice(int | Money $renewalPrice): self
    {
        $clone = clone $this;

        if ($renewalPrice instanceof Money) {
            $clone->renewalPrice = $renewalPrice;
        } else {
            $clone->renewalPrice = new Money(
                $renewalPrice,
                new Currency('USD')
            );
        }

        return $clone;
    }
}
