<?php

declare(strict_types=1);

namespace App\Http\ServiceSuites\StaticCache\Services;

use League\Flysystem\Filesystem;
use Psr\Http\Message\ServerRequestInterface;

class DoesCacheFileExistForRequest
{
    private GetCachePathFromRequest $getCachePathFromRequest;
    private Filesystem $filesystem;

    public function __construct(
        GetCachePathFromRequest $getCachePathFromRequest,
        Filesystem $filesystem
    ) {
        $this->getCachePathFromRequest = $getCachePathFromRequest;
        $this->filesystem              = $filesystem;
    }

    public function __invoke(ServerRequestInterface $request): bool
    {
        return $this->filesystem->fileExists(
            ($this->getCachePathFromRequest)($request)
        );
    }
}
