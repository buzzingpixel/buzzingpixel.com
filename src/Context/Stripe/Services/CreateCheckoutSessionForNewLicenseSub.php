<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Stripe\Factories\FetchPricesForSoftwareFactory;
use App\Context\Stripe\Factories\StripeFactory;
use App\Utilities\SystemClock;
use Config\General;
use DateTimeImmutable;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\StripeClient;

use function assert;

class CreateCheckoutSessionForNewLicenseSub
{
    private StripeClient $stripeClient;

    public function __construct(
        private General $config,
        StripeFactory $stripeFactory,
        private SystemClock $systemClock,
        private FetchPricesForSoftwareFactory $fetchPricesForSoftwareFactory,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function create(License $license): Session
    {
        $prices = $this->fetchPricesForSoftwareFactory->createFetchPricesForSoftware(
            software: $license->softwareGuarantee(),
        )->fetch();

        $price = $prices->filter(
            static fn (Price $p) => $p->type === 'recurring',
        )->first();

        $link = $this->config->siteUrl() . $license->accountLink();

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
                ],
            ],
        ];

        $currentTime = $this->systemClock->getCurrentTimeUtc();

        $expiresAt = $license->expiresAt();

        assert($expiresAt instanceof DateTimeImmutable);

        if ($expiresAt > $currentTime) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $days = (int) $currentTime->diff($expiresAt)
                ->format('%a');

            if ($days > 7) {
                $params['subscription_data']['trial_period_days'] = $days;
            }
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->stripeClient->checkout->sessions->create(
            $params
        );
    }
}
