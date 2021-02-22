<?php

declare(strict_types=1);

namespace Config;

use App\Globals;
use App\Http\Utilities\Segments\ExtractUriSegments;
use App\Templating\TwigExtensions\BreakToSpace;
use App\Templating\TwigExtensions\CaseConverter;
use App\Templating\TwigExtensions\PhpFunctions;
use App\Templating\TwigExtensions\ReadJson;
use App\Templating\TwigExtensions\SiteUrl;
use BuzzingPixel\TwigMarkdown\MarkdownTwigExtension;
use buzzingpixel\twigsmartypants\SmartypantsTwigExtension;
use buzzingpixel\twigswitch\SwitchTwigExtension;
use buzzingpixel\twigwidont\WidontTwigExtension;
use Psr\Container\ContainerInterface;
use Twig\Loader\FilesystemLoader;

use function assert;

class Twig
{
    public const PATHS = [
        FilesystemLoader::MAIN_NAMESPACE => '/assets/templates',
        'error' => '/src/Http/Response/Error',
        'home' => '/src/Http/Response/Home',
        'software' => '/src/Http/Response/Software',
    ];

    public const EXTENSIONS = [
        BreakToSpace::class,
        CaseConverter::class,
        MarkdownTwigExtension::class,
        PhpFunctions::class,
        ReadJson::class,
        SiteUrl::class,
        SmartypantsTwigExtension::class,
        SwitchTwigExtension::class,
        WidontTwigExtension::class,
    ];

    /**
     * @return mixed[]
     */
    public static function globals(ContainerInterface $di): array
    {
        $extractUriSegments = $di->get(ExtractUriSegments::class);

        assert($extractUriSegments instanceof ExtractUriSegments);

        return [
            'GeneralConfig' => $di->get(General::class),
            'Request' => Globals::request(),
            'UriSegments' => $extractUriSegments(
                Globals::request()->getUri()
            ),
        ];
    }
}
