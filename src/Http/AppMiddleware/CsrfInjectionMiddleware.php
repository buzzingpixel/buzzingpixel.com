<?php

declare(strict_types=1);

namespace App\Http\AppMiddleware;

use App\Factories\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Csrf\Guard;
use Throwable;

use function str_replace;

class CsrfInjectionMiddleware implements MiddlewareInterface
{
    private Guard $csrfGuard;
    private StreamFactory $streamFactory;

    public function __construct(
        Guard $csrfGuard,
        StreamFactory $streamFactory
    ) {
        $this->csrfGuard     = $csrfGuard;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @throws Throwable
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $handler->handle(request: $request);

        $bodyString = $response->getBody()->__toString();

        $bodyString = str_replace(
            search: '{!--csrfTokenNameKey--}',
            replace: $this->csrfGuard->getTokenNameKey(),
            subject: $bodyString,
        );

        $bodyString = str_replace(
            search: '{!--csrfTokenName--}',
            replace: (string) $this->csrfGuard->getTokenName(),
            subject: $bodyString,
        );

        $bodyString = str_replace(
            search: '{!--csrfTokenValueKey--}',
            replace: $this->csrfGuard->getTokenValueKey(),
            subject: $bodyString,
        );

        $bodyString = str_replace(
            search: '{!--csrfTokenValue--}',
            replace: (string) $this->csrfGuard->getTokenValue(),
            subject: $bodyString,
        );

        $body = $this->streamFactory->make();

        $body->write(string: $bodyString);

        return $response->withBody(body: $body);
    }
}
