<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use App\Context\Orders\OrderApi;
use App\Context\Stripe\Contracts\SyncOrder;
use App\Context\Stripe\Services\StripeFetchInvoices;
use App\Context\Stripe\Services\SyncOrderNoOp;
use App\Context\Stripe\Services\SyncOrderOneTime;
use App\Context\Stripe\Services\SyncOrderSubscription;
use App\Context\Users\Entities\User;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use Stripe\Invoice;
use Stripe\InvoiceLineItem;
use Stripe\PaymentIntent;

use function assert;

class SyncOrderFactory
{
    public function __construct(
        private OrderApi $orderApi,
        private SyncOrderNoOp $syncInvoiceNoOp,
        private SyncOrderOneTime $syncOrderOneTime,
        private StripeFetchInvoices $stripeFetchInvoices,
        private SyncOrderSubscription $syncOrderSubscription,
    ) {
    }

    public function createSyncOrder(
        PaymentIntent $paymentIntent,
        User $user,
    ): SyncOrder {
        // If the payment did not succeed, we're not interested in it
        if ($paymentIntent->status !== 'succeeded') {
            return $this->syncInvoiceNoOp;
        }

        // Check for an order for this payment intent
        $order = $this->orderApi->fetchOneOrder(
            queryBuilder: (new OrderQueryBuilder())
                ->withStripeId($paymentIntent->id)
                ->withUserId($user->id()),
        );

        // If we've already synced this order, no need to do it again
        if ($order !== null) {
            return $this->syncInvoiceNoOp;
        }

        // This is a subscription
        if ($paymentIntent->invoice !== null) {
            $invoiceId = $paymentIntent->invoice;

            $invoice = $this->stripeFetchInvoices->fetch([
                'customer' => $user->userStripeId(),
            ])->filter(
                static fn (Invoice $i) => $i->id === $invoiceId,
            )->first();

            // Make sure this came from BuzzingPixel.com
            foreach ($invoice->lines as $line) {
                assert($line instanceof InvoiceLineItem);
                $licenseKey = (string) $line->metadata['license_key'];
                $softwareId = (string) $line->metadata['software_id'];

                if ($licenseKey !== '' && $softwareId !== '') {
                    return $this->syncOrderSubscription;
                }
            }

            // If we didn't find the license key and software ID, this item
            // is not one we created
            return $this->syncInvoiceNoOp;
        }

        // Make sure this came from BuzzingPixel.com
        $licenseKey = (string) ($paymentIntent->metadata['license_key'] ?? '');
        $softwareId = (string) ($paymentIntent->metadata['software_id'] ?? '');
        if ($licenseKey === '' || $softwareId === '') {
            return $this->syncInvoiceNoOp;
        }

        // This was a one time payment
        return $this->syncOrderOneTime;
    }
}
