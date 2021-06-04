<?php

declare(strict_types=1);

namespace App\Context\Stripe\Entities;

use App\Collections\AbstractCollection;
use Stripe\Product;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Product $element)
 * @method Product first()
 * @method Product last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method StripeProductCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method StripeProductCollection filter(callable $callback)
 * @method StripeProductCollection where(string $propertyOrMethod, $value)
 * @method StripeProductCollection map(callable $callback)
 * @method Product[] toArray()
 * @method Product|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Product $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<Product>
 */
class StripeProductCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Product::class;
    }
}
