<?php

declare(strict_types=1);

namespace App\Context\Stripe\Entities;

use App\Collections\AbstractCollection;
use Stripe\TaxRate;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(TaxRate $element)
 * @method TaxRate first()
 * @method TaxRate last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method StripeTaxRateCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method StripeTaxRateCollection filter(callable $callback)
 * @method StripeTaxRateCollection where(string $propertyOrMethod, $value)
 * @method StripeTaxRateCollection map(callable $callback)
 * @method TaxRate[] toArray()
 * @method TaxRate|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, TaxRate $item, bool $setLastIfNoMatch = false)
 * @method StripeTaxRateCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<TaxRate>
 */
class StripeTaxRateCollection extends AbstractCollection
{
    public function getType(): string
    {
        return TaxRate::class;
    }
}
