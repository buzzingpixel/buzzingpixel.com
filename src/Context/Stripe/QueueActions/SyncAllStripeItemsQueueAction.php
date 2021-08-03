<?php

declare(strict_types=1);

namespace App\Context\Stripe\QueueActions;

use App\Context\Stripe\LocalStripeApi;

class SyncAllStripeItemsQueueAction
{
    public function __construct(private LocalStripeApi $stripeApi)
    {
    }

    public function sync(): void
    {
        $this->stripeApi->syncCustomers();
        $this->stripeApi->syncProducts();
        $this->stripeApi->syncLicenses();
    }
}
