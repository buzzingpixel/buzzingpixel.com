<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Context\Licenses\Services\GenerateLicenseKey;
use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderItem;
use App\Context\Orders\OrderApi;
use App\Context\Software\Entities\Software;
use App\Context\Software\Services\FetchSoftwareByStripePriceId;
use App\Context\Stripe\Contracts\SyncInvoice as SyncInvoiceContract;
use App\Context\Users\Entities\User;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Stripe\Invoice;
use Stripe\InvoiceLineItem;
use Stripe\Plan;
use Stripe\Price;
use Stripe\StripeObject;

use function assert;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class SyncInvoiceCreateOrder implements SyncInvoiceContract
{
    public function __construct(
        private OrderApi $orderApi,
        private LicenseApi $licenseApi,
        private GenerateLicenseKey $generateLicenseKey,
        private FetchSoftwareByStripePriceId $fetchSoftwareByStripePriceId,
    ) {
    }

    public function sync(Invoice $invoice, User $user): void
    {
        $address = $invoice->customer_address;

        assert($address instanceof StripeObject);

        $order = new Order(
            stripeId: $invoice->id,
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

        $lineItems = [];

        foreach ($invoice->lines->data as $lineItem) {
            assert($lineItem instanceof InvoiceLineItem);

            $stripePrice = $lineItem->price;

            assert($stripePrice instanceof Price);

            $software = $this->fetchSoftwareByStripePriceId->fetch(
                $stripePrice->id,
            );

            $softwareId = $software->id();

            if (! isset($lineItems[$lineItem->description])) {
                $lineItems[$softwareId] = [
                    'amount' => 0,
                    'quantity' => 0,
                    'software' => null,
                    'subscriptionPlan' => null,
                ];
            }

            $lineItems[$softwareId]['amount'] += $lineItem->amount;

            $lineItems[$softwareId]['quantity'] = $lineItem->quantity;

            $lineItems[$softwareId]['software'] = $software;

            $plan = $lineItem->plan;

            if ($plan === null) {
                continue;
            }

            $lineItems[$softwareId]['subscriptionPlan'] = $plan;
        }

        foreach ($lineItems as $lineItem) {
            $software = $lineItem['software'];

            $subscriptionPlan = $lineItem['subscriptionPlan'];

            $expiresAt = null;

            /** @phpstan-ignore-next-line  */
            assert($subscriptionPlan instanceof Plan || $subscriptionPlan === null);

            assert($software instanceof Software);

            $stripeId = '';

            // TODO: Check if a license for this plan ID exists
            if ($subscriptionPlan !== null) {
                $stripeId = $subscriptionPlan->id;

                /** @noinspection PhpUnhandledExceptionInspection */
                $expiresAt = (new DateTimeImmutable(
                    'now',
                    new DateTimeZone('UTC')
                ))
                    ->setTimestamp(
                        $subscriptionPlan->created
                    )
                    // Possible TODO: This assumes subscriptions are 1 year
                    ->add(new DateInterval('P1Y'));
            }

            $license = new License(
                majorVersion: $software->versions()->first()->majorVersion(),
                licenseKey: $this->generateLicenseKey->generate(),
                expiresAt: $expiresAt,
                user: $user,
                software: $software,
                stripeId: $stripeId,
            );

            $this->licenseApi->saveLicense($license);

            $order = $order->withAddedOrderItem(new OrderItem(
                price: $lineItem['amount'],
                originalPrice: 0,
                quantity: (int) $lineItem['quantity'],
                order: $order,
                license: $license,
                software: $software,
            ));
        }

        $this->orderApi->saveOrder($order);
    }
}
