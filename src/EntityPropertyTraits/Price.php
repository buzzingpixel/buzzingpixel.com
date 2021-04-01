<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\MoneyFormatter;
use Money\Currency;
use Money\Money;

use function ltrim;

trait Price
{
    private Money $price;

    public function price(): Money
    {
        return $this->price;
    }

    public function priceAsInt(): int
    {
        return (int) $this->price->getAmount();
    }

    public function priceFormatted(): string
    {
        return MoneyFormatter::format($this->price);
    }

    public function priceFormattedNoSymbol(): string
    {
        return ltrim($this->priceFormatted(), '$');
    }

    /**
     * @return $this
     */
    public function withPrice(int | Money $price): self
    {
        $clone = clone $this;

        if ($price instanceof Money) {
            $clone->price = $price;
        } else {
            $clone->price = new Money(
                $price,
                new Currency('USD')
            );
        }

        return $clone;
    }
}
