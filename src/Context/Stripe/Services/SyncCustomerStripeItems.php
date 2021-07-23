<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Users\Entities\User;
use Stripe\Customer;

class SyncCustomerStripeItems
{
    public function __construct(
        private SyncCustomerStripeItemsInvoices $syncInvoices,
    ) {
    }

    public function sync(
        Customer $customer,
        User $user,
    ): void {
        $this->syncInvoices->sync($customer, $user);
    }
}
