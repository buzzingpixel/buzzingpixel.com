<?php

declare(strict_types=1);

namespace App\Context\Stripe\Entities;

use App\Collections\AbstractCollection;
use Stripe\Price;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Price $element)
 * @method Price first()
 * @method Price last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method StripePriceCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method StripePriceCollection filter(callable $callback)
 * @method StripePriceCollection where(string $propertyOrMethod, $value)
 * @method StripePriceCollection map(callable $callback)
 * @method Price[] toArray()
 * @method Price|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Price $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<Price>
 */
class StripePriceCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Price::class;
    }
}
