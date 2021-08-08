<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Factories\SyncOrderFactory;
use App\Context\Users\Entities\User;
use Stripe\Customer;
use Stripe\PaymentIntent;

class SyncCustomerPaymentIntents
{
    public function __construct(
        private SyncOrderFactory $syncOrderFactory,
        private StripeFetchPaymentIntents $stripeFetchPaymentIntents,
    ) {
    }

    public function sync(
        Customer $customer,
        User $user,
    ): void {
        $paymentIntents = $this->stripeFetchPaymentIntents->fetch([
            'customer' => $customer->id,
        ]);

        $paymentIntents->map(
            function (PaymentIntent $paymentIntent) use (
                $user
            ): void {
                $this->syncOrderFactory->createSyncOrder(
                    paymentIntent: $paymentIntent,
                    user: $user,
                )->sync(
                    paymentIntent: $paymentIntent,
                    user: $user,
                );
            }
        );
    }
}
