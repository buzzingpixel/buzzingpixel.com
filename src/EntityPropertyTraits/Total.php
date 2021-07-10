<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\MoneyFormatter;
use Money\Currency;
use Money\Money;

use function ltrim;

trait Total
{
    private Money $total;

    public function total(): Money
    {
        return $this->total;
    }

    public function totalAsInt(): int
    {
        return (int) $this->total->getAmount();
    }

    public function totalFormatted(): string
    {
        return MoneyFormatter::format($this->total);
    }

    public function totalFormattedNoSymbol(): string
    {
        return ltrim($this->totalFormatted(), '$');
    }

    /**
     * @return $this
     */
    public function withTotal(int | Money $total): self
    {
        $clone = clone $this;

        if ($total instanceof Money) {
            $clone->total = $total;
        } else {
            $clone->total = new Money(
                $total,
                new Currency('USD')
            );
        }

        return $clone;
    }
}
