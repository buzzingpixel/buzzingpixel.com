<?php

declare(strict_types=1);

namespace App\Context\Stripe\Contracts;

interface SyncProduct
{
    public function sync(): void;
}
