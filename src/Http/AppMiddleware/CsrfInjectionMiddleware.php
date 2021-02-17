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
        $response = $handler->handle($request);

        $bodyString = $response->getBody()->__toString();

        $bodyString = str_replace(
            '{!--csrfTokenNameKey--}',
            $this->csrfGuard->getTokenNameKey(),
            $bodyString,
        );

        $bodyString = str_replace(
            '{!--csrfTokenName--}',
            (string) $this->csrfGuard->getTokenName(),
            $bodyString,
        );

        $bodyString = str_replace(
            '{!--csrfTokenValueKey--}',
            $this->csrfGuard->getTokenValueKey(),
            $bodyString,
        );

        $bodyString = str_replace(
            '{!--csrfTokenValue--}',
            (string) $this->csrfGuard->getTokenValue(),
            $bodyString,
        );

        $body = $this->streamFactory->make();

        $body->write($bodyString);

        return $response->withBody($body);
    }
}
