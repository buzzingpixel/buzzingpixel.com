<?php

declare(strict_types=1);

use App\Context\RedisCache\CacheItemPool as RedisCacheItemPool;
use Psr\Cache\CacheItemPoolInterface;

use function DI\autowire;

return [
    // CacheItemPoolInterface::class => autowire(App\Context\DatabaseCache\CacheItemPool::class),
    CacheItemPoolInterface::class => autowire(RedisCacheItemPool::class),

    Redis::class => static function (): Redis {
        $redis = new Redis();

        /**
         * @psalm-suppress UndefinedClass
         * @phpstan-ignore-next-line
         */
        $redis->connect((string) getenv('REDIS_HOST'));

        return $redis;
    },
];
