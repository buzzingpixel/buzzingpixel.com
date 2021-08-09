<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Utilities\MoneyFormatter;
use Money\Currency;
use Money\Money;

use function ltrim;

trait StripeSubscriptionAmount
{
    private Money $stripeSubscriptionAmount;

    public function stripeSubscriptionAmount(): Money
    {
        return $this->stripeSubscriptionAmount;
    }

    public function stripeSubscriptionAmountAsInt(): int
    {
        return (int) $this->stripeSubscriptionAmount->getAmount();
    }

    public function stripeSubscriptionAmountFormatted(): string
    {
        return MoneyFormatter::format($this->stripeSubscriptionAmount);
    }

    public function stripeSubscriptionAmountFormattedNoSymbol(): string
    {
        return ltrim($this->stripeSubscriptionAmountFormatted(), '$');
    }

    /**
     * @return $this
     */
    public function withStripeSubscriptionAmount(int | Money $stripeSubscriptionAmount): self
    {
        $clone = clone $this;

        if ($stripeSubscriptionAmount instanceof Money) {
            $clone->stripeSubscriptionAmount = $stripeSubscriptionAmount;
        } else {
            $clone->stripeSubscriptionAmount = new Money(
                $stripeSubscriptionAmount,
                new Currency('USD')
            );
        }

        return $clone;
    }
}
