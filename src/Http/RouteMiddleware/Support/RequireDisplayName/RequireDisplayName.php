<?php

declare(strict_types=1);

namespace App\Http\RouteMiddleware\Support\RequireDisplayName;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\RouteMiddleware\Support\RequireDisplayName\Factories\RequireDisplayNameResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequireDisplayName implements MiddlewareInterface
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private RequireDisplayNameResponderFactory $responderFactory,
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        return $this->responderFactory->getResponder(
            loggedInUser: $this->loggedInUser,
        )->respond(
            request: $request,
            handler: $handler,
        );
    }
}
