<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\MoneyFormatter;
use Money\Currency;
use Money\Money;

use function ltrim;

trait Tax
{
    private Money $tax;

    public function tax(): Money
    {
        return $this->tax;
    }

    public function taxAsInt(): int
    {
        return (int) $this->tax->getAmount();
    }

    public function taxFormatted(): string
    {
        return MoneyFormatter::format($this->tax);
    }

    public function taxFormattedNoSymbol(): string
    {
        return ltrim($this->taxFormatted(), '$');
    }

    /**
     * @return $this
     */
    public function withTax(int | Money $tax): self
    {
        $clone = clone $this;

        if ($tax instanceof Money) {
            $clone->tax = $tax;
        } else {
            $clone->tax = new Money(
                $tax,
                new Currency('USD')
            );
        }

        return $clone;
    }
}
