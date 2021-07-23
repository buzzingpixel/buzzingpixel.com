<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Contracts\SyncInvoice;
use App\Context\Users\Entities\User;
use Stripe\Invoice;

class SyncInvoiceNoOp implements SyncInvoice
{
    public function sync(Invoice $invoice, User $user): void
    {
    }
}
