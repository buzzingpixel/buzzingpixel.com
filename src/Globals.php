<?php

declare(strict_types=1);

namespace App;

use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;
use Throwable;

class Globals
{
    private static bool $hasInitialized = false;
    private static ContainerInterface $di;
    private static ?ServerRequestInterface $request = null;
    private static App $app;

    /**
     * @throws Throwable
     */
    public static function init(ContainerInterface $di): void
    {
        if (self::$hasInitialized) {
            throw new Exception(
                'Singleton Globals may only call init once',
            );
        }

        self::$di = $di;

        self::$hasInitialized = true;
    }

    public static function di(): ContainerInterface
    {
        return self::$di;
    }

    public static function setRequest(ServerRequestInterface $request): void
    {
        self::$request = $request;
    }

    public static function request(): ServerRequestInterface
    {
        return self::$request ?? ServerRequestCreatorFactory::create()
                ->createServerRequestFromGlobals();
    }

    public static function setApp(App $app): void
    {
        self::$app = $app;
    }

    public static function getApp(): App
    {
        return self::$app;
    }
}
