<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeBalanceTransaction
{
    /**
     * @Mapping\Column(
     *     name="stripe_balance_transaction",
     *     type="string",
     * )
     */
    protected string $stripeBalanceTransaction = '';

    public function getStripeBalanceTransaction(): string
    {
        return $this->stripeBalanceTransaction;
    }

    public function setStripeBalanceTransaction(
        string $stripeBalanceTransaction
    ): void {
        $this->stripeBalanceTransaction = $stripeBalanceTransaction;
    }
}
