<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Licenses\Services\GenerateLicenseKey;
use App\Context\Software\Entities\Software;
use App\Context\Stripe\Entities\StripeCheckoutSessionContainer;
use App\Context\Stripe\Factories\FetchPricesForSoftwareFactory;
use App\Context\Stripe\Factories\StripeFactory;
use App\Context\Users\Entities\User;
use App\Templating\TwigExtensions\SiteUrl;
use Stripe\Price;
use Stripe\StripeClient;

class CreateCheckoutSessionForSoftware
{
    private StripeClient $stripeClient;

    public function __construct(
        private SiteUrl $siteUrl,
        StripeFactory $stripeFactory,
        private GenerateLicenseKey $generateLicenseKey,
        private FetchPricesForSoftwareFactory $fetchPricesFactory,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function create(
        Software $software,
        User $user,
    ): StripeCheckoutSessionContainer {
        $prices = $this->fetchPricesFactory->createFetchPricesForSoftware(
            software: $software,
        )->fetch();

        $metaData = [
            'license_key' => $this->generateLicenseKey->generate(),
            'software_id' => $software->id(),
        ];

        $params = [
            'cancel_url' => $this->siteUrl->siteUrl($software->pageLink()),
            'mode' => $software->isSubscription() ? 'subscription' : 'payment',
            'payment_method_types' => ['card'],
            'success_url' => $this->siteUrl->siteUrl(
                uri: '/account/post-checkout'
            ),
            'customer' => $user->userStripeId(),
            'line_items' => $prices->mapToArray(
                static fn (Price $p) => [
                    'price' => $p->id,
                    'quantity' => 1,
                    // 'description' => $p->type === 'recurring' ?
                    //     'Updates and support subscription' :
                    //     'Base price',
                ],
            ),
        ];

        if ($software->isSubscription()) {
            $params['subscription_data'] = ['metadata' => $metaData];
        } else {
            $params['payment_intent_data'] = ['metadata' => $metaData];
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return new StripeCheckoutSessionContainer(
            session: $this->stripeClient->checkout->sessions->create(
                $params
            ),
        );
    }
}
