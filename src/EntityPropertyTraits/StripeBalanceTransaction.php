<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripeBalanceTransaction
{
    private string $stripeBalanceTransaction;

    public function stripeBalanceTransaction(): string
    {
        return $this->stripeBalanceTransaction;
    }

    /**
     * @return $this
     */
    public function withStripeBalanceTransaction(
        string $stripeBalanceTransaction
    ): self {
        $clone = clone $this;

        $clone->stripeBalanceTransaction = $stripeBalanceTransaction;

        return $clone;
    }
}
