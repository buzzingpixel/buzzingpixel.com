<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Stripe\Entities\StripeCheckoutSessionContainer;
use App\Context\Stripe\Factories\FetchPricesForSoftwareFactory;
use App\Context\Stripe\Factories\StripeFactory;
use App\Templating\TwigExtensions\SiteUrl;
use Stripe\Price;
use Stripe\StripeClient;

class CreateCheckoutSessionForLicense
{
    private StripeClient $stripeClient;

    public function __construct(
        private SiteUrl $siteUrl,
        StripeFactory $stripeFactory,
        private FetchPricesForSoftwareFactory $fetchPricesForSoftwareFactory,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function create(License $license): StripeCheckoutSessionContainer
    {
        $software = $license->softwareGuarantee();

        $prices = $this->fetchPricesForSoftwareFactory->createFetchPricesForSoftware(
            software: $software,
        )->fetch();

        $price = $prices->filter(
            static fn (Price $p) => $p->type === 'recurring',
        )->first();

        $link = $this->siteUrl->siteUrl($license->accountLink());

        $params = [
            'cancel_url' => $link,
            'mode' => 'subscription',
            'payment_method_types' => ['card'],
            'success_url' => $link,
            'customer' => $license->userGuarantee()->userStripeId(),
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
            'subscription_data' => [
                'metadata' => [
                    'license_key' => $license->licenseKey(),
                    'software_id' => $software->id(),
                ],
            ],
        ];

        /** @noinspection PhpUnhandledExceptionInspection */
        return new StripeCheckoutSessionContainer(
            session: $this->stripeClient->checkout->sessions->create(
                $params
            ),
        );
    }
}
