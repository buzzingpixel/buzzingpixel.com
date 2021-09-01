<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderItem;
use App\Context\Orders\OrderApi;
use App\Context\Software\Entities\Software;
use App\Context\Software\SoftwareApi;
use App\Context\Stripe\Contracts\SyncOrder as SyncOrderContract;
use App\Context\Users\Entities\User;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use DateTimeImmutable;
use DateTimeZone;
use Stripe\Charge;
use Stripe\Invoice;
use Stripe\InvoiceLineItem;
use Stripe\PaymentIntent;
use Stripe\StripeObject;

use function assert;

class SyncOrderSubscription implements SyncOrderContract
{
    public function __construct(
        private OrderApi $orderApi,
        private LicenseApi $licenseApi,
        private SoftwareApi $softwareApi,
        private StripeFetchInvoices $stripeFetchInvoices,
    ) {
    }

    public function sync(PaymentIntent $paymentIntent, User $user): void
    {
        $invoiceId = $paymentIntent->invoice;

        $invoice = $this->stripeFetchInvoices->fetch([
            'customer' => $user->userStripeId(),
        ])->filter(
            static fn (Invoice $i) => $i->id === $invoiceId,
        )->first();

        $charge = $paymentIntent->charges->first();

        assert($charge instanceof Charge);

        $billingDetails = $charge->billing_details;

        /** @phpstan-ignore-next-line */
        $address = $billingDetails->address;

        assert($address instanceof StripeObject);

        $order = new Order(
            stripeId: $paymentIntent->id,
            stripeAmount: (string) $invoice->amount_due,
            stripeCreated: (string) $invoice->created,
            stripeCurrency: $invoice->currency,
            stripePaid: $invoice->paid,
            subTotal: $invoice->subtotal,
            tax: $invoice->tax ?? 0,
            total: $invoice->total,
            billingName: (string) $invoice->customer_name,
            billingCompany: $user->billingProfile()->billingCompany(),
            billingPhone: (string) $invoice->customer_phone,
            /** @phpstan-ignore-next-line  */
            billingCountryRegion: (string) $address->country,
            /** @phpstan-ignore-next-line  */
            billingAddress: (string) $address->line1,
            /** @phpstan-ignore-next-line  */
            billingAddressContinued: (string) $address->line2,
            /** @phpstan-ignore-next-line  */
            billingCity: (string) $address->city,
            /** @phpstan-ignore-next-line  */
            billingStateProvince: (string) $address->state,
            /** @phpstan-ignore-next-line  */
            billingPostalCode: (string) $address->postal_code,
            orderDate: (new DateTimeImmutable())
                ->setTimezone(new DateTimeZone('UTC'))
                ->setTimestamp($invoice->created),
            user: $user,
        );

        $licenseKey = '';

        $softwareId = '';

        $subscriptionId = $invoice->subscription;

        $subscriptionItemId = null;

        $price = 0;

        $expiresAt = null;

        $subscriptionAmount = 0;

        foreach ($invoice->lines->data as $lineItem) {
            assert($lineItem instanceof InvoiceLineItem);

            $price += $lineItem->amount;

            /** @psalm-suppress DocblockTypeContradiction */
            if (
                $lineItem->subscription_item === null ||
                $lineItem->subscription_item === ''
            ) {
                continue;
            }

            $subscriptionItemId = $lineItem->subscription_item;

            $licenseKey = (string) $lineItem->metadata['license_key'];

            $softwareId = (string) $lineItem->metadata['software_id'];

            /** @phpstan-ignore-next-line */
            $expiresAtTimeStamp = (int) $lineItem->period->end;

            $expiresAt = (new DateTimeImmutable())
                ->setTimezone(new DateTimeZone('UTC'))
                ->setTimestamp($expiresAtTimeStamp);

            $subscriptionAmount = $lineItem->amount;
        }

        $license = $this->licenseApi->fetchOneLicense(
            queryBuilder: (new LicenseQueryBuilder())
                ->withLicenseKey(value: $licenseKey),
        );

        if ($license === null) {
            $license = new License();
        }

        $software = $this->softwareApi->fetchOneSoftware(
            queryBuilder: (new SoftwareQueryBuilder())
                ->withId(value: $softwareId),
        );

        assert($software instanceof Software);

        $license = $license->withLicenseKey(licenseKey: $licenseKey)
            ->withMajorVersion(
                majorVersion: $software->versions()->first()->majorVersion(),
            )
            ->withMaxVersion(
                maxVersion: $software->versions()->first()->version(),
            )
            ->withExpiresAt(expiresAt: $expiresAt)
            ->withUser(user: $user)
            ->withSoftware(software: $software)
            ->withStripeStatus(stripeStatus: License::STRIPE_STATUS_ACTIVE)
            ->withStripeSubscriptionId(
                stripeSubscriptionId: (string) $subscriptionId
            )
            ->withStripeSubscriptionItemId(
                stripeSubscriptionItemId: (string) $subscriptionItemId
            )
            ->withStripeCanceledAt(stripeCanceledAt: null)
            ->withStripeSubscriptionAmount(
                stripeSubscriptionAmount: $subscriptionAmount
            );

        $this->licenseApi->saveLicense($license);

        $order = $order->withAddedOrderItem(newOrderItem: new OrderItem(
            price: $price,
            originalPrice: 0,
            quantity: 1,
            order: $order,
            license: $license,
            software: $software,
        ));

        $this->orderApi->saveOrder($order);
    }
}
