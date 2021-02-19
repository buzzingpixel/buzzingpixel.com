<?php

declare(strict_types=1);

namespace Config\Factories;

use App\Templating\TwigExtensions\TemplateExists;
use BuzzingPixel\TwigDumper\TwigDumper;
use Config\Twig;
use Psr\Container\ContainerInterface;
use Throwable;
use Twig\Environment as TwigEnvironment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

use function class_exists;
use function dirname;
use function getenv;

class TwigEnvironmentFactory
{
    /**
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $di): TwigEnvironment
    {
        $debug = (bool) getenv('DEV_MODE');

        $projectPath = dirname(__DIR__, 2);

        $loader = $di->get(FilesystemLoader::class);

        foreach (Twig::PATHS as $nameSpace => $path) {
            $loader->addPath(
                $projectPath . $path,
                $nameSpace
            );
        }

        $twig = new TwigEnvironment(
            $loader,
            [
                'debug' => $debug,
                'cache' => $debug ? false : $projectPath . '/storage/twig',
                'strict_variables' => $debug,
            ]
        );

        if ($debug) {
            $twig->addExtension($di->get(DebugExtension::class));

            if (class_exists(TwigDumper::class)) {
                $twig->addExtension($di->get(TwigDumper::class));
            }
        }

        $twig->addExtension(
            new TemplateExists($twig->getLoader())
        );

        foreach (Twig::EXTENSIONS as $extClassString) {
            $twig->addExtension($di->get($extClassString));
        }

        foreach (Twig::globals($di) as $name => $val) {
            $twig->addGlobal($name, $val);
        }

        return $twig;
    }
}
