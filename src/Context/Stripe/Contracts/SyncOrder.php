<?php

declare(strict_types=1);

namespace App\Context\Stripe\Contracts;

use App\Context\Users\Entities\User;
use Stripe\PaymentIntent;

interface SyncOrder
{
    public function sync(PaymentIntent $paymentIntent, User $user): void;
}
