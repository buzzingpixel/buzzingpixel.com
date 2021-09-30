<?php

declare(strict_types=1);

namespace App\Http\AppMiddleware;

use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function in_array;
use function mb_strtolower;

class EnforceExpectedHostMiddleware implements MiddlewareInterface
{
    public function __construct(
        private General $config,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $method = mb_strtolower($request->getMethod());

        if (
            $this->config->expectedHost() === '' ||
            $this->config->expectedRedirect() === '' ||
            ! in_array($method, ['get', 'head'], true)
        ) {
            return $handler->handle($request);
        }

        if ($this->config->expectedHost() === $request->getUri()->getHost()) {
            return $handler->handle($request);
        }

        return $this->responseFactory->createResponse(301)
            ->withHeader(
                'Location',
                $this->config->expectedRedirect()
            );
    }
}
