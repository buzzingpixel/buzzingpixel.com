<?php

declare(strict_types=1);

namespace App\Context\Stripe;

use App\Context\Cart\Entities\Cart;
use App\Context\Stripe\Services\CreateBillingPortalSession;
use App\Context\Stripe\Services\CreateCheckoutSession;
use App\Context\Stripe\Services\SyncCustomers;
use App\Context\Stripe\Services\SyncProducts;
use App\Context\Users\Entities\User;
use Stripe\BillingPortal\Session as BillingSession;
use Stripe\Checkout\Session as CheckoutSession;

class LocalStripeApi
{
    public function __construct(
        private SyncProducts $syncProducts,
        private SyncCustomers $syncCustomers,
        private CreateBillingPortalSession $createBillingPortalSession,
        private CreateCheckoutSession $createCheckoutSession,
    ) {
    }

    public function syncProducts(): void
    {
        $this->syncProducts->sync();
    }

    public function syncCustomers(): void
    {
        $this->syncCustomers->sync();
    }

    public function createBillingPortal(User $user): BillingSession
    {
        return $this->createBillingPortalSession->create($user);
    }

    public function createCheckoutSession(Cart $cart): CheckoutSession
    {
        return $this->createCheckoutSession->create($cart);
    }
}
