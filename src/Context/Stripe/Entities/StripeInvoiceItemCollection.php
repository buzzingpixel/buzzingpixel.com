<?php

declare(strict_types=1);

namespace App\Context\Stripe\Entities;

use App\Collections\AbstractCollection;
use Stripe\InvoiceItem;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(InvoiceItem $element)
 * @method InvoiceItem first()
 * @method InvoiceItem last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method StripeInvoiceItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method StripeInvoiceItemCollection filter(callable $callback)
 * @method StripeInvoiceItemCollection where(string $propertyOrMethod, $value)
 * @method StripeInvoiceItemCollection map(callable $callback)
 * @method InvoiceItem[] toArray()
 * @method InvoiceItem|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, InvoiceItem $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<InvoiceItem>
 */
class StripeInvoiceItemCollection extends AbstractCollection
{
    public function getType(): string
    {
        return InvoiceItem::class;
    }
}
