<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Stripe\Contracts\SyncSubscriptionItem;
use Stripe\Subscription;
use Stripe\SubscriptionItem;

class SyncSubscriptionItemNoOp implements SyncSubscriptionItem
{
    public function sync(
        ?SubscriptionItem $subscriptionItem = null,
        ?Subscription $subscription = null,
        ?License $license = null,
    ): void {
    }
}
