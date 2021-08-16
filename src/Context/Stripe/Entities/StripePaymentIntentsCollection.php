<?php

declare(strict_types=1);

namespace App\Context\Stripe\Entities;

use App\Collections\AbstractCollection;
use Stripe\PaymentIntent;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(PaymentIntent $element)
 * @method PaymentIntent first()
 * @method PaymentIntent last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method StripePaymentIntentsCollection sort(string $propertyOrMethod, string $PaymentIntent = self::SORT_ASC)
 * @method StripePaymentIntentsCollection filter(callable $callback)
 * @method StripePaymentIntentsCollection where(string $propertyOrMethod, $value)
 * @method StripePaymentIntentsCollection map(callable $callback)
 * @method PaymentIntent[] toArray()
 * @method PaymentIntent|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, PaymentIntent $item, bool $setLastIfNoMatch = false)
 * @method StripePaymentIntentsCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<PaymentIntent>
 */
class StripePaymentIntentsCollection extends AbstractCollection
{
    public function getType(): string
    {
        return PaymentIntent::class;
    }
}
