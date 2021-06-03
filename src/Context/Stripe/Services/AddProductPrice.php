<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Factories\StripeFactory;

class AddProductPrice
{
    public function __construct(
        private StripeFactory $stripeFactory,
    ) {
    }

    public function add(
        string $productId,
        int $unitAmountInCents,
        bool $isRecurring = false,
    ): void {
        $params = [
            'currency' => 'usd',
            'product' => $productId,
            'unit_amount' => $unitAmountInCents,
        ];

        if ($isRecurring) {
            $params['recurring'] = [
                'interval' => 'year',
                'usage_type' => 'licensed',
            ];
        }

        $this->stripeFactory->createStripeClient()->prices->create(
            $params,
        );
    }
}
