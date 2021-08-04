<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
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
    public function __construct(private LicenseApi $licenseApi)
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
            $endTimeStamp = $subscription->current_period_end;

            $endTime = (new DateTimeImmutable())
                ->setTimezone(new DateTimeZone('UTC'))
                ->setTimestamp(
                    $endTimeStamp
                );

            $license = $license->withStripeSubscriptionId(
                stripeSubscriptionId: $subscription->id
            )
                ->withStripeSubscriptionItemId(
                    stripeSubscriptionItemId: $subscriptionItem->id
                )
                ->withStripeStatus(stripeStatus: $subscription->status)
                ->withExpiresAt(expiresAt: $endTime);

            $this->licenseApi->saveLicense($license);
        } catch (Throwable) {
        }
    }
}
