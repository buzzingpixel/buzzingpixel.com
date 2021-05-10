<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache\Entities;

use App\Collections\AbstractCollection;
use Psr\Cache\CacheItemInterface;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(DatabaseCacheItem $element)
 * @method DatabaseCacheItem first()
 * @method DatabaseCacheItem last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method DatabaseCacheItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method DatabaseCacheItemCollection filter(callable $callback)
 * @method DatabaseCacheItemCollection where(string $propertyOrMethod, $value)
 * @method DatabaseCacheItemCollection map(callable $callback)
 * @method DatabaseCacheItem[] toArray()
 * @method DatabaseCacheItem|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, DatabaseCacheItem|CacheItemInterface $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<DatabaseCacheItem>
 */
class DatabaseCacheItemCollection extends AbstractCollection
{
    public function getType(): string
    {
        return DatabaseCacheItem::class;
    }
}
