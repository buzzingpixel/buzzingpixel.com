<?php

declare(strict_types=1);

namespace App\Http\RouteMiddleware\Support\RequireDisplayName\Contracts;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface RequireDisplayNameResponderContract
{
    public function respond(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface;
}
