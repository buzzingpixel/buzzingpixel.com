<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use App\Context\Stripe\Contracts\SyncCustomer;
use App\Context\Stripe\Services\SyncCustomerAddNew;
use App\Context\Stripe\Services\SyncCustomerExisting;
use App\Context\Stripe\Services\SyncCustomerNoOp;
use App\Context\Users\Entities\User;
use Stripe\Customer;

class SyncCustomerFactory
{
    public function __construct(
        private StripeFactory $stripeFactory,
    ) {
    }

    public function createSyncCustomer(
        ?Customer $customer = null,
        ?User $user = null,
    ): SyncCustomer {
        // If customer exists locally but not in stripe
        if ($customer === null && $user !== null) {
            return new SyncCustomerAddNew(
                stripeFactory:  $this->stripeFactory,
                user: $user,
            );
        }

        // If customer exists in both places
        if ($customer !== null && $user !== null) {
            return new SyncCustomerExisting(
                stripeFactory:  $this->stripeFactory,
                user: $user,
                customer: $customer,
            );
        }

        return new SyncCustomerNoOp();
    }
}
