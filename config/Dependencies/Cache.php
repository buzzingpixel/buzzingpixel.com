<?php

declare(strict_types=1);

use App\Context\DatabaseCache\CacheItemPool;
use Psr\Cache\CacheItemPoolInterface;

use function DI\autowire;

return [
    CacheItemPoolInterface::class => autowire(CacheItemPool::class),
];
