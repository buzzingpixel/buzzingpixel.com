<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\MoneyFormatter;
use Money\Currency;
use Money\Money;

use function ltrim;

trait OriginalPrice
{
    private Money $originalPrice;

    public function originalPrice(): Money
    {
        return $this->originalPrice;
    }

    public function originalPriceAsInt(): int
    {
        return (int) $this->originalPrice->getAmount();
    }

    public function originalPriceFormatted(): string
    {
        return MoneyFormatter::format($this->originalPrice);
    }

    public function originalPriceFormattedNoSymbol(): string
    {
        return ltrim($this->originalPriceFormatted(), '$');
    }

    /**
     * @return $this
     */
    public function withOriginalPrice(int | Money $originalPrice): self
    {
        $clone = clone $this;

        if ($originalPrice instanceof Money) {
            $clone->originalPrice = $originalPrice;
        } else {
            $clone->originalPrice = new Money(
                $originalPrice,
                new Currency('USD')
            );
        }

        return $clone;
    }
}
