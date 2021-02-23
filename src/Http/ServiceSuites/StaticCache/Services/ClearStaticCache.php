<?php

declare(strict_types=1);

namespace App\Http\ServiceSuites\StaticCache\Services;

use Config\General;

use function exec;

/**
 * Ignored from test coverage because of the exec command
 */
class ClearStaticCache
{
    public function __construct(private General $generalConfig)
    {
    }

    public function __invoke(): void
    {
        $storagePath = $this->generalConfig->pathToStorageDirectory();

        $staticCachePath = $storagePath . '/static-cache/*';

        exec(command: 'rm -rf ' . $staticCachePath);
    }
}
