<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Contracts\SyncCustomer;

class SyncCustomerNoOp implements SyncCustomer
{
    public function sync(): void
    {
    }
}
