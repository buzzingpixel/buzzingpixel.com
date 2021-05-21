<?php

declare(strict_types=1);

namespace App\Http\Response\Cart;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CartLogIn
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): ResponseInterface
    {
        if ($this->loggedInUser->hasUser()) {
            return $this->responseFactory->createResponse(303)
                ->withHeader('Location', '/cart');
        }

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/RouteMiddleware/LogIn/RequireLogIn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Log In',
                ),
                'redirectTo' => '/cart',
                'breadcrumbSingle' => [
                    'content' => 'Cart',
                    'uri' => '/cart',
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Cart',
                        'uri' => '/cart',
                    ],
                    ['content' => 'Log In'],
                ],
            ]
        ));

        return $response;
    }
}
