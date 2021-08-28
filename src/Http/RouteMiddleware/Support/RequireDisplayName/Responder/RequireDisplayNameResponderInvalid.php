<?php

declare(strict_types=1);

namespace App\Http\RouteMiddleware\Support\RequireDisplayName\Responder;

use App\Http\RouteMiddleware\Support\RequireDisplayName\Contracts\RequireDisplayNameResponderContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;

class RequireDisplayNameResponderInvalid implements RequireDisplayNameResponderContract
{
    /**
     * @throws HttpNotFoundException
     */
    public function respond(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        throw new HttpNotFoundException($request);
    }
}
