<?php

declare(strict_types=1);

namespace App\Http\RouteMiddleware\LogIn;

use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RequireLogInResponder
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function respond(
        Meta $meta,
        string $redirectTo,
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/RouteMiddleware/LogIn/RequireLogIn.twig',
            [
                'meta' => $meta,
                'redirectTo' => $redirectTo,
            ]
        ));

        return $response;
    }
}
