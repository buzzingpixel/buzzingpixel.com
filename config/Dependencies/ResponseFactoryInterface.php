<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;

use function DI\autowire;

return [
    ResponseFactoryInterface::class => autowire(ResponseFactory::class),
];
