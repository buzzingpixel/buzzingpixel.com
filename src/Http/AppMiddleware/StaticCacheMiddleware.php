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
            return $response = $handler->handle($request);
        }

        $cached = $this->staticCacheApi->doesCacheFileExistForRequest(
            $request
        );

        return $cached ?
            $this->createResponseFromCache($request) :
            $this->handle($request, $handler);
    }

    /**
     * @throws Throwable
     */
    private function createResponseFromCache(
        ServerRequestInterface $request
    ): ResponseInterface {
        return $this->staticCacheApi->createResponseFromCache(
            $request
        );
    }

    /**
     * @throws Throwable
     */
    private function handle(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $handler->handle($request);

        $enableStaticCache = $response->getHeader(
            'EnableStaticCache'
        )[0] ?? false;

        if ($enableStaticCache !== 'true') {
            return $response;
        }

        $this->staticCacheApi->createCacheFromResponse(
            $response->withoutHeader('EnableStaticCache'),
            $request
        );

        return $this->createResponseFromCache($request);
    }
}
