<?php

declare(strict_types=1);

namespace App\Context\Stripe;

use App\Context\Stripe\Services\SyncProducts;

class LocalStripeApi
{
    public function __construct(
        private SyncProducts $syncProducts,
    ) {
    }

    public function syncProducts(): void
    {
        $this->syncProducts->sync();
    }
}
