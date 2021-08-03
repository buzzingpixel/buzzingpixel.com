<?php

declare(strict_types=1);

namespace App\Context\Stripe\Contracts;

use App\Context\Licenses\Entities\License;
use Stripe\Subscription;
use Stripe\SubscriptionItem;

interface SyncSubscriptionItem
{
    public function sync(
        ?SubscriptionItem $subscriptionItem = null,
        ?Subscription $subscription = null,
        ?License $license = null,
    ): void;
}
