<?php

declare(strict_types=1);

use App\Globals;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;

require dirname(__DIR__) . '/vendor/autoload.php';

if ((bool) getenv('DEV_MODE')) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

if (class_exists(WhoopsRun::class)) {
    $whoops = new WhoopsRun();
    $whoops->prependHandler(
        mb_strtolower(PHP_SAPI) === 'cli' ?
            new PlainTextHandler() :
            new PrettyPageHandler()
    );
    $whoops->register();
}

if (class_exists(Symfony\Component\VarDumper\VarDumper::class)) {
    require __DIR__ . '/dumper.php';
}

return static function (): ContainerInterface {
    $diCacheDir = dirname(__DIR__) . '/storage/di-cache';

    $containerBuilder = (new ContainerBuilder())
        ->useAnnotations(true)
        ->useAutowiring(true)
        ->ignorePhpDocErrors(true)
        ->addDefinitions(require __DIR__ . '/dependencies.php');

    if ((bool) getenv('ENABLE_DI_COMPILATION')) {
        if (! is_dir($diCacheDir)) {
            mkdir($diCacheDir, 0777, true);
        }

        $containerBuilder->enableCompilation($diCacheDir);

        $containerBuilder->writeProxiesToFile(
            true,
            $diCacheDir
        );
    }

    $container = $containerBuilder->build();

    Globals::init($container);

    return $container;
};
