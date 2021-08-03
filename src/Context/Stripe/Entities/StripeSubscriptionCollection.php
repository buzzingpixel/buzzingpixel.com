<?php

declare(strict_types=1);

namespace App\Context\Stripe\Entities;

use App\Collections\AbstractCollection;
use Stripe\Subscription;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Subscription $element)
 * @method Subscription first()
 * @method Subscription last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method StripeSubscriptionCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method StripeSubscriptionCollection filter(callable $callback)
 * @method StripeSubscriptionCollection where(string $propertyOrMethod, $value)
 * @method StripeSubscriptionCollection map(callable $callback)
 * @method Subscription[] toArray()
 * @method Subscription|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Subscription $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<Subscription>
 */
class StripeSubscriptionCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Subscription::class;
    }
}
