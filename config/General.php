<?php

declare(strict_types=1);

namespace Config;

use Config\Abstractions\SimpleModel;

use function assert;
use function dirname;
use function getenv;
use function is_string;

/**
 * @method bool devMode()
 * @method string rootPath()
 * @method string publicPath()
 * @method string pathToStorageDirectory()
 * @method string siteUrl()
 * @method string siteName()
 * @method string systemEmailSenderAddress()
 * @method string systemEmailSenderName()
 * @method array stylesheets()
 * @method array jsFiles()
 */
class General extends SimpleModel
{
    public function __construct()
    {
        $rootPath = dirname(__DIR__);

        static::$devMode = (bool) getenv('DEV_MODE');

        static::$rootPath = $rootPath;

        static::$publicPath = $rootPath . '/public';

        static::$pathToStorageDirectory = $rootPath . '/storage';

        if (getenv('SITE_URL') !== false) {
            $siteUrl = getenv('SITE_URL');

            assert(is_string($siteUrl));

            static::$siteUrl = $siteUrl;
        }

        if (
            ! static::$devMode ||
            ! (bool) getenv('USE_DYNAMIC_SITE_URL') ||
            ! isset($_SERVER['HTTP_HOST'])
        ) {
            return;
        }

        static::$siteUrl = 'https://' . ((string) $_SERVER['HTTP_HOST']);
    }

    public static bool $devMode = false;

    public static string $rootPath = '';

    public static string $publicPath = '';

    public static string $pathToStorageDirectory = '';

    public static string $siteUrl = 'https://www.buzzingpixel.com';

    public static string $siteName = 'BuzzingPixel';

    public static string $systemEmailSenderAddress = 'info@buzzingpixel.com';

    public static string $systemEmailSenderName = 'BuzzingPixel';

    /** @var string[] */
    public static array $stylesheets = [];

    /** @var string[] */
    public static array $jsFiles = ['https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js'];
}
