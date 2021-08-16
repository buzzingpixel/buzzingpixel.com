<?php

declare(strict_types=1);

namespace App\Context\Stripe\Entities;

use App\Collections\AbstractCollection;
use Stripe\Invoice;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Invoice $element)
 * @method Invoice first()
 * @method Invoice last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method StripeInvoiceCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method StripeInvoiceCollection filter(callable $callback)
 * @method StripeInvoiceCollection where(string $propertyOrMethod, $value)
 * @method StripeInvoiceCollection map(callable $callback)
 * @method Invoice[] toArray()
 * @method Invoice|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Invoice $item, bool $setLastIfNoMatch = false)
 * @method StripeInvoiceCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<Invoice>
 */
class StripeInvoiceCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Invoice::class;
    }
}
