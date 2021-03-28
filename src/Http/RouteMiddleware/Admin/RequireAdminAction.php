<?php

declare(strict_types=1);

namespace App\Http\RouteMiddleware\Admin;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RequireAdminAction implements MiddlewareInterface
{
    public function __construct(
        private RequireAdminResponder $responder,
        private LoggedInUser $loggedInUser,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (
            ! $this->loggedInUser->hasUser() ||
            ! $this->loggedInUser->user()->isAdmin()
        ) {
            return $this->responder->respond(
                new Meta(
                    metaTitle: 'Unauthorized',
                ),
            );
        }

        return $handler->handle($request);
    }
}
