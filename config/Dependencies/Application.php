<?php

declare(strict_types=1);

use App\Globals;
use Slim\Interfaces\RouteParserInterface;

return [
    RouteParserInterface::class => static function (): RouteParserInterface {
        return Globals::getApp()->getRouteCollector()->getRouteParser();
    },
];
