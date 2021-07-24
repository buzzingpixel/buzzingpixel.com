<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use App\Context\Orders\OrderApi;
use App\Context\Stripe\Contracts\SyncInvoice;
use App\Context\Stripe\Services\SyncInvoiceCreateOrder as SyncInvoiceService;
use App\Context\Stripe\Services\SyncInvoiceNoOp;
use App\Context\Users\Entities\User;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use Stripe\Invoice;

class SyncInvoiceFactory
{
    public function __construct(
        private OrderApi $orderApi,
        private SyncInvoiceService $syncInvoice,
        private SyncInvoiceNoOp $syncInvoiceNoOp,
    ) {
    }

    public function createSyncInvoice(
        Invoice $invoice,
        User $user,
    ): SyncInvoice {
        if (! $invoice->paid) {
            return $this->syncInvoiceNoOp;
        }

        $order = $this->orderApi->fetchOneOrder(
            queryBuilder: (new OrderQueryBuilder())
                ->withStripeId($invoice->id)
                ->withUserId($user->id()),
        );

        if ($order === null) {
            return $this->syncInvoice;
        }

        return $this->syncInvoiceNoOp;
    }
}
