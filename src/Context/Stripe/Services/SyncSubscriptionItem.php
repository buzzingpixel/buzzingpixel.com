<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Services\SaveLicense;
use App\Context\Stripe\Contracts\SyncSubscriptionItem as SyncSubscriptionItemContract;
use DateTimeImmutable;
use DateTimeZone;
use Stripe\Subscription;
use Stripe\SubscriptionItem;
use Throwable;

use function assert;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class SyncSubscriptionItem implements SyncSubscriptionItemContract
{
    public function __construct(private SaveLicense $saveLicense)
    {
    }

    public function sync(
        ?SubscriptionItem $subscriptionItem = null,
        ?Subscription $subscription = null,
        ?License $license = null,
    ): void {
        assert($subscriptionItem instanceof SubscriptionItem);
        assert($subscription instanceof Subscription);
        assert($license instanceof License);

        try {
            $canceledAt = null;

            $canceledAtTimeStamp = $subscription->cancel_at;

            if ($canceledAtTimeStamp !== null) {
                $canceledAt = (new DateTimeImmutable())
                    ->setTimezone(new DateTimeZone('UTC'))
                    ->setTimestamp($canceledAtTimeStamp);
            }

            $endTimeStamp = $subscription->current_period_end;

            $endTime = (new DateTimeImmutable())
                ->setTimezone(new DateTimeZone('UTC'))
                ->setTimestamp($endTimeStamp);

            $license = $license->withStripeSubscriptionId(
                stripeSubscriptionId: $subscription->id
            )
                ->withStripeSubscriptionItemId(
                    stripeSubscriptionItemId: $subscriptionItem->id
                )
                ->withStripeStatus(stripeStatus: $subscription->status)
                ->withExpiresAt(expiresAt: $endTime)
                ->withStripeCanceledAt(stripeCanceledAt: $canceledAt);

            $this->saveLicense->save($license);
        } catch (Throwable) {
        }
    }
}
