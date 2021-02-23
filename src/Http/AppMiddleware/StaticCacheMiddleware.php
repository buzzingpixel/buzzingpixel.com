<?php

declare(strict_types=1);

namespace App\Http\AppMiddleware;

use App\Http\ServiceSuites\StaticCache\StaticCacheApi;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class StaticCacheMiddleware implements MiddlewareInterface
{
    private StaticCacheApi $staticCacheApi;
    private bool $staticCacheEnabled;

    public function __construct(
        StaticCacheApi $staticCacheApi,
        bool $staticCacheEnabled
    ) {
        $this->staticCacheApi     = $staticCacheApi;
        $this->staticCacheEnabled = $staticCacheEnabled;
    }

    /**
     * @throws Throwable
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (! $this->staticCacheEnabled) {
            return $response = $handler->handle(request: $request);
        }

        $cached = $this->staticCacheApi->doesCacheFileExistForRequest(
            request: $request
        );

        return $cached ?
            $this->createResponseFromCache(request : $request) :
            $this->handle(request: $request, handler: $handler);
    }

    /**
     * @throws Throwable
     */
    private function createResponseFromCache(
        ServerRequestInterface $request
    ): ResponseInterface {
        return $this->staticCacheApi->createResponseFromCache(
            request: $request
        );
    }

    /**
     * @throws Throwable
     */
    private function handle(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $handler->handle(request: $request);

        $enableStaticCache = $response->getHeader(
            name: 'EnableStaticCache'
        )[0] ?? false;

        if ($enableStaticCache !== 'true') {
            return $response;
        }

        $this->staticCacheApi->createCacheFromResponse(
            response: $response->withoutHeader(name: 'EnableStaticCache'),
            request: $request
        );

        return $this->createResponseFromCache(request: $request);
    }
}
