<?php

declare(strict_types=1);

use App\Templating\TwigExtensions\TemplateExists;
use BuzzingPixel\TwigDumper\TwigDumper;
use Config\Twig;
use Psr\Container\ContainerInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Extension\DebugExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;

return [
    TwigEnvironment::class => static function (ContainerInterface $di): TwigEnvironment {
        $debug = (bool) getenv('DEV_MODE');

        $projectPath = dirname(__DIR__, 2);

        $loader = $di->get(FilesystemLoader::class);

        assert($loader instanceof FilesystemLoader);

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
            $debugExt = $di->get(DebugExtension::class);

            assert($debugExt instanceof DebugExtension);

            $twig->addExtension($debugExt);

            if (class_exists(TwigDumper::class)) {
                $dumper = $di->get(TwigDumper::class);

                assert($dumper instanceof TwigDumper);

                $twig->addExtension($dumper);
            }
        }

        $twig->addExtension(
            new TemplateExists($twig->getLoader())
        );

        foreach (Twig::EXTENSIONS as $extClassString) {
            /** @psalm-suppress MixedAssignment */
            $ext = $di->get($extClassString);

            assert($ext instanceof ExtensionInterface);

            $twig->addExtension($ext);
        }

        /** @psalm-suppress MixedAssignment */
        foreach (Twig::globals($di) as $name => $val) {
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $twig->addGlobal($name, $val);
        }

        return $twig;
    },
];
