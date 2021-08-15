<?php

declare(strict_types=1);

namespace App\Context\RedisCache;

use App\Context\Cache\Entities\CacheItem;
use App\Context\Cache\Entities\CacheItemCollection;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Redis;

use function array_map;
use function array_walk;
use function assert;
use function serialize;
use function unserialize;

class CacheItemPool implements CacheItemPoolInterface
{
    public function __construct(private Redis $redis)
    {
    }

    public function getItem(string $key): CacheItemInterface
    {
        /** @psalm-suppress MixedAssignment */
        $redisItem = $this->redis->get($key);

        if ($redisItem === false) {
            return new CacheItem(key: $key);
        }

        /** @psalm-suppress MixedAssignment */
        $ttl = $this->redis->ttl($key);

        /**
         * @noinspection PhpUnhandledExceptionInspection
         * @psalm-suppress MixedArgument
         */
        return new CacheItem(
            key: $key,
            value: unserialize($redisItem),
            expiresAt: $ttl > 0 ? (new DateTimeImmutable(
                'now',
                new DateTimeZone('UTC')
            ))->add(new DateInterval(
                'PT' . $this->redis->ttl($key) . 'S'
            )) : null,
            isHit: true,
        );
    }

    /**
     * @param string[] $keys
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     * @phpstan-ignore-next-line
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function getItems(array $keys = []): iterable
    {
        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress ArgumentTypeCoercion
         */
        return new CacheItemCollection(array_map(
            fn (string $k) => $this->getItem($k),
            $keys,
        ));
    }

    public function hasItem(string $key): bool
    {
        return (bool) $this->redis->exists($key);
    }

    /** @psalm-suppress MixedInferredReturnType */
    public function clear(): bool
    {
        /** @psalm-suppress MixedReturnStatement */
        return $this->redis->flushAll();
    }

    public function deleteItem(string $key): bool
    {
        return $this->redis->del($key) === 1;
    }

    public function deleteItems(array $keys): bool
    {
        return $this->redis->del($keys) > 1;
    }

    public function save(CacheItemInterface $item): bool
    {
        assert($item instanceof CacheItem);

        $expires = $item->expires();

        $value = serialize($item->get());

        if ($expires !== null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $currentTime = new DateTimeImmutable(
                'now',
                new DateTimeZone('UTC')
            );

            /** @psalm-suppress MixedReturnStatement */
            return $this->redis->setex(
                $item->getKey(),
                $expires->getTimestamp() - $currentTime->getTimestamp(),
                $value,
            );
        }

        /** @psalm-suppress MixedReturnStatement */
        return $this->redis->set(
            $item->getKey(),
            $value,
        );
    }

    private array $deferred = [];

    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->deferred[] = $item;

        return true;
    }

    public function commit(): bool
    {
        array_walk(
            $this->deferred,
            fn (CacheItemInterface $i) => $this->save($i),
        );

        $this->deferred = [];
    }
}
