<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Contracts\SyncOrder;
use App\Context\Users\Entities\User;
use Stripe\PaymentIntent;

class SyncOrderNoOp implements SyncOrder
{
    public function sync(PaymentIntent $paymentIntent, User $user): void
    {
    }
}
