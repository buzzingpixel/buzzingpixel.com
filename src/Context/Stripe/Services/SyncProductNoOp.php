<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Contracts\SyncProduct;

class SyncProductNoOp implements SyncProduct
{
    public function sync(): void
    {
    }
}
