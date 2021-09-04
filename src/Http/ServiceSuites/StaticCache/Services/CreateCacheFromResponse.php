<?php

declare(strict_types=1);

namespace App\Http\ServiceSuites\StaticCache\Services;

use App\Http\ServiceSuites\StaticCache\Models\CacheItem;
use League\Flysystem\Filesystem;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function serialize;

class CreateCacheFromResponse
{
    public function __construct(
        private Filesystem $filesystem,
        private GetCachePathFromRequest $getCachePathFromRequest
    ) {
    }

    public function __invoke(
        ResponseInterface $response,
        ServerRequestInterface $request
    ): void {
        $cacheItem = new CacheItem();

        $cacheItem->statusCode = $response->getStatusCode();

        $cacheItem->reasonPhrase = $response->getReasonPhrase();

        $cacheItem->protocolVersion = $response->getProtocolVersion();

        $cacheItem->headers = $response->getHeaders();

        $cacheItem->body = $response->getBody()->__toString();

        $this->filesystem->write(
            ($this->getCachePathFromRequest)($request),
            serialize($cacheItem),
        );
    }
}
