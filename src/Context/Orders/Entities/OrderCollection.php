<?php

declare(strict_types=1);

namespace App\Context\Orders\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Order $element)
 * @method Order first()
 * @method Order last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method OrderCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method OrderCollection filter(callable $callback)
 * @method OrderCollection where(string $propertyOrMethod, $value)
 * @method OrderCollection map(callable $callback)
 * @method Order[] toArray()
 * @method Order|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Order $item, bool $setLastIfNoMatch = false)
 * @method OrderCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<Order>
 */
class OrderCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Order::class;
    }
}
