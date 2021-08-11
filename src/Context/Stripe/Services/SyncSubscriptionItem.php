<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Services\SaveLicense;
use App\Context\Stripe\Contracts\SyncSubscriptionItem as SyncSubscriptionItemContract;
use App\Utilities\SystemClock;
use DateTimeImmutable;
use DateTimeZone;
use Stripe\Subscription;
use Stripe\SubscriptionItem;
use Throwable;

use function assert;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class SyncSubscriptionItem implements SyncSubscriptionItemContract
{
    public function __construct(
        private SaveLicense $saveLicense,
        private SystemClock $systemClock,
        private StripeFetchInvoices $stripeFetchInvoices,
    ) {
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

            $cancelAtTimeStamp   = $subscription->cancel_at;
            $canceledAtTimeStamp = $subscription->canceled_at;
            $endTimeStamp        = $subscription->current_period_end;

            if (
                $canceledAtTimeStamp !== null &&
                $cancelAtTimeStamp < $canceledAtTimeStamp
            ) {
                $canceledAt = (new DateTimeImmutable())
                    ->setTimezone(new DateTimeZone('UTC'))
                    ->setTimestamp($canceledAtTimeStamp);
            } elseif ($cancelAtTimeStamp !== null) {
                $canceledAt = (new DateTimeImmutable())
                    ->setTimezone(new DateTimeZone('UTC'))
                    ->setTimestamp($cancelAtTimeStamp);
            }

            if (
                $canceledAt !== null &&
                $endTimeStamp > $canceledAt->getTimestamp()
            ) {
                $endTimeStamp = $canceledAt->getTimestamp();
            }

            $endTime = (new DateTimeImmutable())
                ->setTimezone(new DateTimeZone('UTC'))
                ->setTimestamp($endTimeStamp);

            $subscriptionAmount = (int) $subscriptionItem->price->unit_amount;

            $license = $license->withStripeSubscriptionId(
                stripeSubscriptionId: $subscription->id
            )
                ->withStripeSubscriptionItemId(
                    stripeSubscriptionItemId: $subscriptionItem->id
                )
                ->withStripeStatus(stripeStatus: $subscription->status)
                ->withExpiresAt(expiresAt: $endTime)
                ->withStripeCanceledAt(stripeCanceledAt: $canceledAt)
            ->withStripeSubscriptionAmount(
                stripeSubscriptionAmount: $subscriptionAmount
            );

            $this->saveLicense->save($license);
        } catch (Throwable) {
        }
    }
}
