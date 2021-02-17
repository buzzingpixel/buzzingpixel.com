<?php

declare(strict_types=1);

namespace App\Http\ServiceSuites\TwigCache;

use ReflectionClass;
use Twig\Cache\FilesystemCache;
use Twig\Environment;

use function assert;
use function exec;
use function rtrim;

class TwigCacheApi
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Returns (bool) true if cache was cleared.
     * Returns (bool) false if cache is not enabled in this environment and
     *     cannot be cleared
     */
    public function clearTwigCache(): bool
    {
        $cache = $this->twig->getCache(false);

        $isInstance = $cache instanceof FilesystemCache;

        if (! $isInstance) {
            return false;
        }

        assert($cache instanceof FilesystemCache);

        $reflection = new ReflectionClass($cache);

        $directory = $reflection->getProperty('directory');

        $directory->setAccessible(true);

        $cacheDirGlob = rtrim(
            (string) $directory->getValue($cache),
            '/'
        ) . '/*';

        exec('rm -rf ' . $cacheDirGlob);

        return true;
    }
}
