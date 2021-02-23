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
        $cache = $this->twig->getCache(original: false);

        $isInstance = $cache instanceof FilesystemCache;

        if (! $isInstance) {
            return false;
        }

        assert(assertion: $cache instanceof FilesystemCache);

        $reflection = new ReflectionClass($cache);

        $directory = $reflection->getProperty(name: 'directory');

        /** @psalm-suppress InvalidNamedArgument */
        $directory->setAccessible(accessible: true);

        /** @psalm-suppress InvalidNamedArgument */
        $cacheDirGlob = rtrim(
            string: (string) $directory->getValue(object: $cache),
            characters: '/'
        ) . '/*';

        exec(command: 'rm -rf ' . $cacheDirGlob);

        return true;
    }
}
