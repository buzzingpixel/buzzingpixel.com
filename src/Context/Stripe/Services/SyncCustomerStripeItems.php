<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Users\Entities\User;
use Stripe\Customer;

class SyncCustomerStripeItems
{
    public function __construct(
        private SyncCustomerPaymentIntents $syncPaymentIntents,
    ) {
    }

    public function sync(
        Customer $customer,
        User $user,
    ): void {
        $this->syncPaymentIntents->sync(customer: $customer, user: $user);
    }
}
