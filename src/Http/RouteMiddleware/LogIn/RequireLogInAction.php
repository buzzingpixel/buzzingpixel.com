<?php

declare(strict_types=1);

namespace App\Http\RouteMiddleware\LogIn;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RequireLogInAction implements MiddlewareInterface
{
    public function __construct(
        private RequireLogInResponder $responder,
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
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if (
            ! $this->loggedInUser->hasUser() ||
            ! $this->loggedInUser->user()->isActive()
        ) {
            return $this->responder->respond(
                new Meta(
                    metaTitle: 'Log In',
                ),
                $request->getUri()->getPath(),
            );
        }

        return $handler->handle($request);
    }
}
