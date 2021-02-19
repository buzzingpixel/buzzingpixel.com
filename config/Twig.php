<?php

declare(strict_types=1);

namespace Config;

use App\Globals;
use App\Http\Utilities\Segments\ExtractUriSegments;
use App\Templating\TwigExtensions\BreakToSpace;
use App\Templating\TwigExtensions\PhpFunctions;
use App\Templating\TwigExtensions\ReadJson;
use App\Templating\TwigExtensions\SiteUrl;
use BuzzingPixel\TwigMarkdown\MarkdownTwigExtension;
use buzzingpixel\twigsmartypants\SmartypantsTwigExtension;
use buzzingpixel\twigswitch\SwitchTwigExtension;
use buzzingpixel\twigwidont\WidontTwigExtension;
use Psr\Container\ContainerInterface;
use Twig\Loader\FilesystemLoader;

class Twig
{
    public const PATHS = [
        FilesystemLoader::MAIN_NAMESPACE => '/assets/templates',
        'error' => '/src/Http/Response/Error',
        'home' => '/src/Http/Response/Home',
    ];

    public const EXTENSIONS = [
        PhpFunctions::class,
        SmartypantsTwigExtension::class,
        WidontTwigExtension::class,
        SwitchTwigExtension::class,
        ReadJson::class,
        MarkdownTwigExtension::class,
        BreakToSpace::class,
        SiteUrl::class,
    ];

    public static function globals(ContainerInterface $di): array
    {
        return [
            'GeneralConfig' => $di->get(General::class),
            'Request' => Globals::request(),
            'UriSegments' => $di->get(ExtractUriSegments::class)(
                Globals::request()->getUri()
            ),
        ];
    }
}
