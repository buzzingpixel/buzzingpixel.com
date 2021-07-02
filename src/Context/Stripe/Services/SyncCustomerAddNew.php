<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Contracts\SyncCustomer;
use App\Context\Stripe\Factories\StripeFactory;
use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use Stripe\StripeClient;
use Throwable;

class SyncCustomerAddNew implements SyncCustomer
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private User $user,
        private UserApi $userApi,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function sync(): void
    {
        try {
            $customer = $this->stripeClient->customers->create([
                'address' => [
                    'city' => $this->user->billingProfile()->billingCity(),
                    'country' => $this->user->billingProfile()->billingCountryRegionAlpha3(),
                    'line1' => $this->user->billingProfile()->billingAddress(),
                    'line2' => $this->user->billingProfile()->billingAddressContinued(),
                    'postal_code' => $this->user->billingProfile()->billingPostalCode(),
                    'state' => $this->user->billingProfile()->billingStateProvince(),
                ],
                'email' => $this->user->emailAddress(),
                'metadata' => ['id' => $this->user->id()],
                'name' => $this->user->billingProfile()->billingName(),
                'phone' => $this->user->billingProfile()->billingPhone(),
            ]);

            $user = $this->user->withUserStripeId($customer->id);

            $this->userApi->saveUser($user);
        } catch (Throwable) {
        }
    }
}
