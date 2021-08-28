<?php

declare(strict_types=1);

namespace App\Http\RouteMiddleware\Support\RequireDisplayName\Responder;

use App\Http\Entities\Meta;
use App\Http\RouteMiddleware\Support\RequireDisplayName\Contracts\RequireDisplayNameResponderContract;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment as TwigEnvironment;

class RequireDisplayNameResponderRequire implements RequireDisplayNameResponderContract
{
    public function __construct(
        private TwigEnvironment $twig,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/RouteMiddleware/Support/RequireDisplayName/Templates/RequireDisplayName.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Display Name Required',
                ),
            ],
        ));

        return $response;
    }
}
