<?php

declare(strict_types=1);

namespace App\Http\ServiceSuites\StaticCache\Services;

use League\Flysystem\Filesystem;
use Psr\Http\Message\ServerRequestInterface;

class DoesCacheFileExistForRequest
{
    public function __construct(
        private GetCachePathFromRequest $getCachePathFromRequest,
        private Filesystem $filesystem
    ) {
    }

    public function __invoke(ServerRequestInterface $request): bool
    {
        return $this->filesystem->fileExists(
            location: ($this->getCachePathFromRequest)(request: $request)
        );
    }
}
