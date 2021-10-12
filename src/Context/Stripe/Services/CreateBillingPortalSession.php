<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Factories\StripeFactory;
use App\Context\Users\Entities\User;
use App\Templating\TwigExtensions\SiteUrl;
use Stripe\BillingPortal\Session;

class CreateBillingPortalSession
{
    public function __construct(
        private SiteUrl $siteUrl,
        private StripeFactory $stripeFactory,
        private StripeFetchCustomers $stripeFetchCustomers,
    ) {
    }

    public function create(User $user): Session
    {
        $customerId = $this->stripeFetchCustomers->fetch(
            ['email' => $user->emailAddress()]
        )->first()->id;

        return $this->stripeFactory->createStripeClient()
            ->billingPortal
            ->sessions
            ->create([
                'customer' => $customerId,
                'return_url' => $this->siteUrl->siteUrl('/account'),
            ]);
    }
}
