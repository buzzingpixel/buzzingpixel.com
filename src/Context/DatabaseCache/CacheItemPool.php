<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache;

use App\Context\DatabaseCache\Entities\DatabaseCacheItem;
use App\Context\DatabaseCache\Entities\DatabaseCacheItemCollection;
use App\Context\DatabaseCache\Services\ClearAllCache;
use App\Context\DatabaseCache\Services\DeleteItemsByKeys;
use App\Context\DatabaseCache\Services\GetItemsByKeys;
use App\Context\DatabaseCache\Services\SaveItem;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

use function array_values;
use function array_walk;
use function count;
use function in_array;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

class CacheItemPool implements CacheItemPoolInterface
{
    public function __construct(
        private GetItemsByKeys $getItemsByKeys,
        private ClearAllCache $clearAllCache,
        private DeleteItemsByKeys $deleteItemsByKeys,
        private SaveItem $saveItem,
    ) {
        $this->runTimeCache = new DatabaseCacheItemCollection();
        $this->deferred     = new DatabaseCacheItemCollection();
    }

    /** @phpstan-ignore-next-line */
    private DatabaseCacheItemCollection $runTimeCache;

    /** @phpstan-ignore-next-line */
    private DatabaseCacheItemCollection $deferred;

    public function getItem(string $key): CacheItemInterface
    {
        $item = $this->runTimeCache
            ->where('getKey', $key)
            ->firstOrNull();

        if ($item !== null) {
            return $item;
        }

        return $this->populateItemsByKeys([$key])
            ->where('getKey', $key)
            ->first();
    }

    /**
     * @param string[] $keys
     *
     * @return DatabaseCacheItemCollection
     *
     * @phpstan-ignore-next-line
     */
    public function getItems(array $keys = []): iterable
    {
        $toQueryKeys = [];

        foreach ($keys as $key) {
            $toQueryKeys[$key] = $key;
        }

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $collection = $this->runTimeCache->filter(
            static function (DatabaseCacheItem $c) use (
                &$toQueryKeys,
                $keys
            ): bool {
                if (isset($toQueryKeys[$c->getKey()])) {
                    /** @psalm-suppress MixedArrayAccess */
                    unset($toQueryKeys[$c->getKey()]);
                }

                return in_array(
                    $c->getKey(),
                    $keys,
                    true
                );
            },
        );

        /** @psalm-suppress MixedArgument */
        if (count($toQueryKeys) < 1) {
            return $collection;
        }

        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         * @psalm-suppress MixedArgument
         */
        $queryCollection = $this->populateItemsByKeys(
            array_values($toQueryKeys),
        );

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $queryCollection->map(
            static fn (DatabaseCacheItem $c) => $collection->add($c),
        );

        return $collection;
    }

    public function hasItem(string $key): bool
    {
        $item = $this->runTimeCache
            ->where('getKey', $key)
            ->firstOrNull();

        if ($item === null) {
            $item = $this->populateItemsByKeys([$key])
                ->first();
        }

        return $item->isHit();
    }

    public function clear(): bool
    {
        $this->clearAllCache->clear();

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
            static function (string $key): void {
                if ($key === '') {
                    throw new InvalidArgumentException();
                }
            }
        );

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $this->deleteItemsByKeys->delete($keys);

        return true;
    }

    public function save(CacheItemInterface $item): bool
    {
        $this->runTimeCache->replaceWhereMatch(
            'getKey',
            $item,
            true,
        );

        $this->saveItem->save($item);

        return true;
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->runTimeCache->replaceWhereMatch(
            'getKey',
            $item,
            true,
        );

        $this->deferred->replaceWhereMatch(
            'getKey',
            $item,
            true,
        );

        return true;
    }

    public function commit(): bool
    {
        if ($this->deferred->isEmpty()) {
            return true;
        }

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $this->deferred->map(
            fn (DatabaseCacheItem $c) => $this->save($c),
        );

        return true;
    }

    /**
     * @param string[] $keys
     *
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @phpstan-ignore-next-line
     */
    private function populateItemsByKeys(array $keys = []): DatabaseCacheItemCollection
    {
        $items = $this->getItemsByKeys->get($keys);

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $items->map(fn (
            DatabaseCacheItem $c
        ) => $this->runTimeCache->add($c));

        return $items;
    }
}
