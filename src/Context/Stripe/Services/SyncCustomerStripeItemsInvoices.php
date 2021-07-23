<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Factories\SyncInvoiceFactory;
use App\Context\Users\Entities\User;
use Stripe\Customer;
use Stripe\Invoice;

class SyncCustomerStripeItemsInvoices
{
    public function __construct(
        private StripeFetchInvoices $stripeFetchInvoices,
        private SyncInvoiceFactory $syncInvoiceFactory,
    ) {
    }

    public function sync(
        Customer $customer,
        User $user,
    ): void {
        $invoices = $this->stripeFetchInvoices->fetch([
            'customer' => $customer->id,
        ]);

        $invoices->map(function (Invoice $invoice) use ($user): void {
            $this->syncInvoiceFactory->createSyncInvoice(
                invoice: $invoice,
                user: $user,
            )->sync(
                invoice: $invoice,
                user: $user,
            );
        });
    }
}
