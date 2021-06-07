<?php

declare(strict_types=1);

namespace App\Context\RequestCache\Entities;

use App\Collections\AbstractCollection;
use Psr\Cache\CacheItemInterface;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(SessionCacheItem $element)
 * @method SessionCacheItem first()
 * @method SessionCacheItem last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method SessionCacheItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method SessionCacheItemCollection filter(callable $callback)
 * @method SessionCacheItemCollection where(string $propertyOrMethod, $value)
 * @method SessionCacheItemCollection map(callable $callback)
 * @method SessionCacheItem[] toArray()
 * @method SessionCacheItem|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, SessionCacheItem|CacheItemInterface $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<SessionCacheItem>
 */
class SessionCacheItemCollection extends AbstractCollection
{
    public function getType(): string
    {
        return SessionCacheItem::class;
    }
}
