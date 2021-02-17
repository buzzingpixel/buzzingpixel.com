<?php

declare(strict_types=1);

namespace App\Http\ServiceSuites\StaticCache\Services;

use App\Http\Utilities\Segments\ExtractUriSegments;
use Config\General;
use Psr\Http\Message\ServerRequestInterface;

class GetCachePathFromRequest
{
    private General $generalConfig;
    private ExtractUriSegments $extractUriSegments;

    public function __construct(
        General $generalConfig,
        ExtractUriSegments $extractUriSegments
    ) {
        $this->generalConfig      = $generalConfig;
        $this->extractUriSegments = $extractUriSegments;
    }

    public function __invoke(ServerRequestInterface $request): string
    {
        $storagePath = $this->generalConfig->pathToStorageDirectory();

        $staticCachePath = $storagePath . '/static-cache';

        $uriSegments = ($this->extractUriSegments)($request->getUri());

        $path = $uriSegments->getPath();

        if ($path !== '') {
            $staticCachePath .= '/' . $path;
        }

        $staticCachePath .= '/index.json';

        return $staticCachePath;
    }
}
