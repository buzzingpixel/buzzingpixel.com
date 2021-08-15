<?php

declare(strict_types=1);

namespace App\Context\Cache\Entities;

use App\Collections\AbstractCollection;
use Psr\Cache\CacheItemInterface;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(CacheItem $element)
 * @method CacheItem first()
 * @method CacheItem last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method CacheItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method CacheItemCollection filter(callable $callback)
 * @method CacheItemCollection where(string $propertyOrMethod, $value)
 * @method CacheItemCollection map(callable $callback)
 * @method CacheItem[] toArray()
 * @method CacheItem|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, CacheItem|CacheItemInterface $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<CacheItem>
 */
class CacheItemCollection extends AbstractCollection
{
    public function getType(): string
    {
        return CacheItem::class;
    }
}
