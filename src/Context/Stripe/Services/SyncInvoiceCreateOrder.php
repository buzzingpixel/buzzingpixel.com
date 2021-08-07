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
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use DateTimeImmutable;
use DateTimeZone;
use Stripe\Invoice;
use Stripe\InvoiceLineItem;
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

            /**
             * @phpstan-ignore-next-line
             */
            $endTimeStamp = (int) $lineItem->period->end;

            $endTime = (new DateTimeImmutable())
                ->setTimezone(new DateTimeZone('UTC'))
                ->setTimestamp(
                    $endTimeStamp
                );

            $stripePrice = $lineItem->price;

            assert($stripePrice instanceof Price);

            $software = $this->fetchSoftwareByStripePriceId->fetch(
                $stripePrice->id,
            );

            $softwareId = $software->id();

            if (! isset($lineItems[$softwareId])) {
                $lineItems[$softwareId] = [
                    'amount' => 0,
                    'quantity' => 0,
                    'software' => null,
                    'subscriptionId' => '',
                    'subscriptionItemId' => '',
                    'subscriptionEndTime' => $endTime,
                    'licenseKey' => '',
                ];
            }

            /** @psalm-suppress MixedArrayAccess */
            $lineItems[$softwareId]['licenseKey'] = (string) ($lineItem['metadata']['license_key'] ?? '');

            if ($lineItems[$softwareId]['licenseKey'] === '') {
                $lineItems[$softwareId]['licenseKey'] = $this->generateLicenseKey->generate();
            }

            $lineItems[$softwareId]['amount'] += $lineItem->amount;

            $lineItems[$softwareId]['quantity'] = $lineItem->quantity;

            $lineItems[$softwareId]['software'] = $software;

            $subscriptionId = $lineItem->subscription;

            $subscriptionItemId = $lineItem->subscription_item;

            /**
             * @psalm-suppress DocblockTypeContradiction
             * @phpstan-ignore-next-line
             */
            if ($subscriptionItemId === null || $subscriptionId === null) {
                continue;
            }

            $lineItems[$softwareId]['subscriptionId'] = $subscriptionId;

            $lineItems[$softwareId]['subscriptionItemId'] = $subscriptionItemId;
        }

        foreach ($lineItems as $lineItem) {
            $software = $lineItem['software'];

            $subscriptionId = $lineItem['subscriptionId'];

            $subscriptionItemId = $lineItem['subscriptionItemId'];

            assert($software instanceof Software);

            $expiresAt = $subscriptionItemId !== '' ?
                $lineItem['subscriptionEndTime'] :
                null;

            $license = $this->licenseApi->fetchOneLicense(
                (new LicenseQueryBuilder())
                    ->withLicenseKey($lineItem['licenseKey']),
            );

            if ($license === null) {
                $license = new License();
            }

            $license = $license
                ->withMajorVersion(
                    majorVersion: $software->versions()->first()->majorVersion()
                )
                ->withLicenseKey(licenseKey: $lineItem['licenseKey'])
                ->withExpiresAt(expiresAt: $expiresAt)
                ->withUser(user: $user)
                ->withSoftware(software: $software)
                ->withStripeSubscriptionId(
                    stripeSubscriptionId:  $subscriptionId
                )
                ->withStripeSubscriptionItemId(
                    stripeSubscriptionItemId: $subscriptionItemId
                )
                ->withStripeStatus(stripeStatus: License::STRIPE_STATUS_ACTIVE)
                ->withStripeCanceledAt(stripeCanceledAt: null);

            $this->licenseApi->saveLicense(license: $license);

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
