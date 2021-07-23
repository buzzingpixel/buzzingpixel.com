<?php

declare(strict_types=1);

namespace App\Context\Stripe\Contracts;

use App\Context\Users\Entities\User;
use Stripe\Invoice;

interface SyncInvoice
{
    public function sync(Invoice $invoice, User $user): void;
}
