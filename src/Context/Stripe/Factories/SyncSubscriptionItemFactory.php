<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use App\Context\Licenses\Entities\License;
use App\Context\Stripe\Contracts\SyncSubscriptionItem as SyncSubscriptionItemContract;
use App\Context\Stripe\Services\SyncSubscriptionItem;
use App\Context\Stripe\Services\SyncSubscriptionItemNoOp;
use Stripe\Subscription;
use Stripe\SubscriptionItem;

class SyncSubscriptionItemFactory
{
    public function __construct(
        private SyncSubscriptionItem $syncSubscriptionItem,
        private SyncSubscriptionItemNoOp $syncSubscriptionItemNoOp,
    ) {
    }

    public function createSyncSubscriptionItem(
        ?SubscriptionItem $subscriptionItem = null,
        ?Subscription $subscription = null,
        ?License $license = null,
    ): SyncSubscriptionItemContract {
        if (
            $subscriptionItem === null ||
            $subscription === null ||
            $license === null
        ) {
            return $this->syncSubscriptionItemNoOp;
        }

        return $this->syncSubscriptionItem;
    }
}
