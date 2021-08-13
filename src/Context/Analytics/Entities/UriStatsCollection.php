<?php

declare(strict_types=1);

namespace App\Context\Analytics\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(UriStats $element)
 * @method UriStats first()
 * @method UriStats last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method UriStatsCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method UriStatsCollection filter(callable $callback)
 * @method UriStatsCollection where(string $propertyOrMethod, $value)
 * @method UriStatsCollection map(callable $callback)
 * @method UriStats[] toArray()
 * @method UriStats|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, UriStats $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<UriStats>
 */
class UriStatsCollection extends AbstractCollection
{
    public function getType(): string
    {
        return UriStats::class;
    }
}
