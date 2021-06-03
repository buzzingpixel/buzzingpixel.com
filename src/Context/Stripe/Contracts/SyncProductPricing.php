<?php

declare(strict_types=1);

namespace App\Context\Stripe\Contracts;

interface SyncProductPricing
{
    public function sync(): void;
}
