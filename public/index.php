<?php

declare(strict_types=1);

use App\Globals;
use App\Http\Response\Error\HttpErrorAction;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;
use Whoops\Run as WhoopsRun;

// Start session
session_start();

$rootDir = dirname(__DIR__);

/**
 * Run bootstrap and get di container
 *
 * @psalm-suppress MixedAssignment
 * @psalm-suppress UnresolvableInclude
 */
$bootstrap = require $rootDir . '/config/bootstrap.php';
/** @psalm-suppress MixedFunctionCall */
$container = $bootstrap();
assert($container instanceof ContainerInterface);

// Create application
AppFactory::setContainer($container);
$app = AppFactory::create();

/**
 * Register routes
 *
 * @psalm-suppress MixedAssignment
 * @psalm-suppress UnresolvableInclude
 */
$routes = require $rootDir . '/config/Routes/index.php';
/** @psalm-suppress MixedFunctionCall */
$routes($app);

// Use factory to get the ServerRequest
$request = ServerRequestCreatorFactory::create()
    ->createServerRequestFromGlobals();

Globals::setRequest($request);

// Register error handlers if Whoops does not exist
if (! class_exists(WhoopsRun::class)) {
    $errorMiddleware = $app->addErrorMiddleware(
        false,
        false,
        false
    );

    $httpErrorAction = $container->get(HttpErrorAction::class);

    assert($httpErrorAction instanceof HttpErrorAction);

    $errorMiddleware->setDefaultErrorHandler($httpErrorAction);
}

/**
 * Register middleware
 *
 * @psalm-suppress MixedAssignment
 * @psalm-suppress UnresolvableInclude
 */
$httpMiddleWares = require $rootDir . '/config/httpAppMiddlewares.php';
/** @psalm-suppress MixedFunctionCall */
$httpMiddleWares($app);

$app->addBodyParsingMiddleware();

// Emit response from app
$responseEmitter = $container->get(ResponseEmitter::class);
assert($responseEmitter instanceof ResponseEmitter);
$responseEmitter->emit($app->handle($request));
