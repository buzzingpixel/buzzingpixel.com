<?php

declare(strict_types=1);

namespace App\Context\Stripe\Contracts;

interface SyncCustomer
{
    public function sync(): void;
}
