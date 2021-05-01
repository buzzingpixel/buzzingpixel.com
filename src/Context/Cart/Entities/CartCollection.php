<?php

declare(strict_types=1);

namespace App\Context\Cart\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Cart $element)
 * @method Cart first()
 * @method Cart last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method CartCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method CartCollection filter(callable $callback)
 * @method CartCollection where(string $propertyOrMethod, $value)
 * @method CartCollection map(callable $callback)
 * @method Cart[] toArray()
 * @method Cart|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Cart $item, bool $setLastIfNoMatch = false)
 */
class CartCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Cart::class;
    }
}
