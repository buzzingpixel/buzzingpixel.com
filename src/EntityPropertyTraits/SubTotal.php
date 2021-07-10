<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\MoneyFormatter;
use Money\Currency;
use Money\Money;

use function ltrim;

trait SubTotal
{
    private Money $subTotal;

    public function subTotal(): Money
    {
        return $this->subTotal;
    }

    public function subTotalAsInt(): int
    {
        return (int) $this->subTotal->getAmount();
    }

    public function subTotalFormatted(): string
    {
        return MoneyFormatter::format($this->subTotal);
    }

    public function subTotalFormattedNoSymbol(): string
    {
        return ltrim($this->subTotalFormatted(), '$');
    }

    /**
     * @return $this
     */
    public function withSubTotal(int | Money $subTotal): self
    {
        $clone = clone $this;

        if ($subTotal instanceof Money) {
            $clone->subTotal = $subTotal;
        } else {
            $clone->subTotal = new Money(
                $subTotal,
                new Currency('USD')
            );
        }

        return $clone;
    }
}
