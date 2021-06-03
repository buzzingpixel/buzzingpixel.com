<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use Config\General;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeFactory
{
    private const STRIPE_VERSION = '2020-08-27';

    private const APP_NAME = 'BuzzingPixel.com';

    private const APP_URL = 'https://www.buzzingpixel.com';

    public function __construct(private General $config)
    {
        Stripe::setApiKey($config->stripeSecretKey());

        Stripe::setApiVersion(self::STRIPE_VERSION);

        Stripe::setAppInfo(
            appName: self::APP_NAME,
            appUrl: self::APP_URL,
        );
    }

    public function createStripeClient(): StripeClient
    {
        return new StripeClient([
            'api_key' => $this->config->stripeSecretKey(),
            'stripe_version' => self::STRIPE_VERSION,
        ]);
    }
}
