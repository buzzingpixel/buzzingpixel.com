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
use App\Context\Stripe\Contracts\SyncOrder;
use App\Context\Users\Entities\User;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use DateTimeImmutable;
use DateTimeZone;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\StripeObject;

use function assert;

class SyncOrderOneTime implements SyncOrder
{
    public function __construct(
        private OrderApi $orderApi,
        private LicenseApi $licenseApi,
        private SoftwareApi $softwareApi,
    ) {
    }

    public function sync(PaymentIntent $paymentIntent, User $user): void
    {
        $charge = $paymentIntent->charges->first();

        assert($charge instanceof Charge);

        $billingDetails = $charge->billing_details;

        /** @phpstan-ignore-next-line */
        $address = $billingDetails->address;

        assert($address instanceof StripeObject);

        $licenseKey = (string) ($paymentIntent->metadata['license_key'] ?? '');
        $softwareId = (string) ($paymentIntent->metadata['software_id'] ?? '');

        $software = $this->softwareApi->fetchOneSoftware(
            queryBuilder: (new SoftwareQueryBuilder())
                ->withId(value: $softwareId),
        );

        assert($software instanceof Software);

        $order = new Order(
            stripeId: $paymentIntent->id,
            stripeAmount: (string) $paymentIntent->amount,
            stripeCreated: (string) $paymentIntent->created,
            stripeCurrency: $paymentIntent->currency,
            stripePaid: true,
            subTotal: $software->priceAsInt(),
            tax: 0,
            total: $paymentIntent->amount,
            /** @phpstan-ignore-next-line */
            billingName: (string) $billingDetails->name,
            billingCompany: $user->billingProfile()->billingCompany(),
            /** @phpstan-ignore-next-line */
            billingPhone: (string) $billingDetails->phone,
            /** @phpstan-ignore-next-line */
            billingCountryRegion: (string) $address->country,
            /** @phpstan-ignore-next-line */
            billingAddress: (string) $address->line1,
            /** @phpstan-ignore-next-line */
            billingAddressContinued: (string) $address->line2,
            /** @phpstan-ignore-next-line */
            billingCity: (string) $address->city,
            /** @phpstan-ignore-next-line */
            billingStateProvince: (string) $address->state,
            /** @phpstan-ignore-next-line */
            billingPostalCode: (string) $address->postal_code,
            orderDate: (new DateTimeImmutable())
                ->setTimezone(new DateTimeZone('UTC'))
                ->setTimestamp($paymentIntent->created),
            user: $user,
        );

        $license = $this->licenseApi->fetchOneLicense(
            queryBuilder: (new LicenseQueryBuilder())
                ->withLicenseKey(value: $licenseKey),
        );

        if ($license === null) {
            $license = new License();
        }

        $license = $license->withLicenseKey(licenseKey: $licenseKey)
            ->withMajorVersion(
                majorVersion: $software->versions()->first()->majorVersion(),
            )
            ->withMaxVersion(
                maxVersion: $software->versions()->first()->version(),
            )
            ->withUser(user: $user)
            ->withSoftware(software: $software);

        $this->licenseApi->saveLicense($license);

        $order = $order->withAddedOrderItem(newOrderItem: new OrderItem(
            price: $paymentIntent->amount,
            originalPrice: 0,
            quantity: 1,
            order: $order,
            license: $license,
            software: $software,
        ));

        $this->orderApi->saveOrder($order);
    }
}
