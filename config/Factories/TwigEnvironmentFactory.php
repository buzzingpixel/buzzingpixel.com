<?php

declare(strict_types=1);

namespace Config\Factories;

use App\Globals;
use App\Http\Utilities\Segments\ExtractUriSegments;
use App\Templating\TwigExtensions\BreakToSpace;
use App\Templating\TwigExtensions\PhpFunctions;
use App\Templating\TwigExtensions\ReadJson;
use App\Templating\TwigExtensions\TemplateExists;
use BuzzingPixel\TwigDumper\TwigDumper;
use BuzzingPixel\TwigMarkdown\MarkdownTwigExtension;
use buzzingpixel\twigsmartypants\SmartypantsTwigExtension;
use buzzingpixel\twigswitch\SwitchTwigExtension;
use buzzingpixel\twigwidont\WidontTwigExtension;
use Config\General;
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

        $loader->addPath($projectPath . '/assets/templates');

        $loader->addPath(
            $projectPath . '/src/Http/Response/Home',
            'home'
        );

        $loader->addPath(
            $projectPath . '/src/Http/Response/Error',
            'error'
        );

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

        $twig->addExtension($di->get(PhpFunctions::class));

        $twig->addExtension($di->get(SmartypantsTwigExtension::class));

        $twig->addExtension($di->get(WidontTwigExtension::class));

        $twig->addExtension($di->get(SwitchTwigExtension::class));

        $twig->addExtension(new TemplateExists($twig->getLoader()));

        $twig->addExtension($di->get(ReadJson::class));

        $twig->addExtension($di->get(MarkdownTwigExtension::class));

        $twig->addExtension($di->get(BreakToSpace::class));

        $twig->addGlobal('GeneralConfig', $di->get(General::class));

        $twig->addGlobal('Request', Globals::request());

        $twig->addGlobal(
            'UriSegments',
            $di->get(ExtractUriSegments::class)(
                Globals::request()->getUri()
            )
        );

        return $twig;
    }
}
