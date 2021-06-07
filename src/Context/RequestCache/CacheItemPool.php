<?php

declare(strict_types=1);

namespace App\Context\RequestCache;

use App\Context\RequestCache\Entities\SessionCacheItem;
use App\Context\RequestCache\Entities\SessionCacheItemCollection;
use InvalidArgumentException;
use LogicException;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

use function array_walk;
use function in_array;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

class CacheItemPool implements CacheItemPoolInterface
{
    /** @phpstan-ignore-next-line  */
    private static SessionCacheItemCollection $runTimeCache;

    public function __construct()
    {
        if (isset(self::$runTimeCache)) {
            return;
        }

        self::$runTimeCache = new SessionCacheItemCollection();
    }

    public function getItem(string $key): CacheItemInterface
    {
        $item = self::$runTimeCache
            ->where('getKey', $key)
            ->firstOrNull();

        if ($item !== null) {
            return $item;
        }

        $item = new SessionCacheItem($key);

        self::$runTimeCache->add($item);

        return $item;
    }

    /**
     * @param string[] $keys
     *
     * @return SessionCacheItemCollection
     *
     * @phpstan-ignore-next-line
     */
    public function getItems(array $keys = []): iterable
    {
        $toCreateKeys = [];

        foreach ($keys as $key) {
            $toCreateKeys[$key] = $key;
        }

        $collection = self::$runTimeCache->filter(
            static function (SessionCacheItem $c) use (
                &$toCreateKeys,
                $keys,
            ): bool {
                if (isset($toCreateKeys[$c->getKey()])) {
                    /** @psalm-suppress MixedArrayAccess */
                    unset($toCreateKeys[$c->getKey()]);
                }

                return in_array(
                    $c->getKey(),
                    $keys,
                    true,
                );
            },
        );

        /** @psalm-suppress MixedAssignment */
        foreach ($toCreateKeys as $key) {
            /** @psalm-suppress MixedArgument */
            $collection->add(new SessionCacheItem($key));
        }

        return $collection;
    }

    public function hasItem(string $key): bool
    {
        return $this->getItem($key)->isHit();
    }

    public function clear(): bool
    {
        self::$runTimeCache = new SessionCacheItemCollection();

        return true;
    }

    public function deleteItem(string $key): bool
    {
        if ($key === '') {
            throw new InvalidArgumentException();
        }

        $this->deleteItems([$key]);

        return true;
    }

    /**
     * @param string[] $keys
     */
    public function deleteItems(array $keys): bool
    {
        array_walk(
            $keys,
            function (string $key): void {
                if ($key === '') {
                    throw new InvalidArgumentException();
                }

                $item = $this->getItem($key);

                /**
                 * @psalm-suppress ArgumentTypeCoercion
                 * @phpstan-ignore-next-line
                 */
                self::$runTimeCache->remove($item);
            }
        );

        return true;
    }

    public function save(CacheItemInterface $item): bool
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        self::$runTimeCache->replaceWhereMatch(
            'getKey',
            $item,
            true,
        );

        return true;
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        throw new LogicException('Not Implemented');
    }

    public function commit(): bool
    {
        throw new LogicException('Not Implemented');
    }
}
