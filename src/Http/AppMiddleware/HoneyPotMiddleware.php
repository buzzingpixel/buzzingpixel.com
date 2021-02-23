<?php

declare(strict_types=1);

namespace App\Http\AppMiddleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;
use Throwable;

use function mb_strtolower;

class HoneyPotMiddleware implements MiddlewareInterface
{
    /**
     * @throws Throwable
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (mb_strtolower($request->getMethod()) === 'get') {
            return $handler->handle(request: $request);
        }

        $post = (array) $request->getParsedBody();

        $honeyPotField = (string) ($post['a_password'] ?? '');

        if ($honeyPotField !== '') {
            throw new HttpBadRequestException(
                request: $request,
                message: 'The honeypot field must not be filled in',
            );
        }

        return $handler->handle(request: $request);
    }
}
