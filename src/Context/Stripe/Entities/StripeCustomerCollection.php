<?php

declare(strict_types=1);

namespace App\Context\Stripe\Entities;

use App\Collections\AbstractCollection;
use Stripe\Customer;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Customer $element)
 * @method Customer first()
 * @method Customer last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method StripeCustomerCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method StripeCustomerCollection filter(callable $callback)
 * @method StripeCustomerCollection where(string $propertyOrMethod, $value)
 * @method StripeCustomerCollection map(callable $callback)
 * @method Customer[] toArray()
 * @method Customer|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Customer $item, bool $setLastIfNoMatch = false)
 * @method StripeCustomerCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<Customer>
 */
class StripeCustomerCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Customer::class;
    }
}
