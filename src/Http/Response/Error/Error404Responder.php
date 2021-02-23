<?php

declare(strict_types=1);

namespace App\Http\Response\Error;

use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Error404Responder
{
    private ResponseFactoryInterface $responseFactory;
    private TwigEnvironment $twig;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        TwigEnvironment $twig
    ) {
        $this->responseFactory = $responseFactory;
        $this->twig            = $twig;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(
            code: 404,
            reasonPhrase: 'Page not found',
        );

        $response->getBody()->write(
            string: $this->twig->render(
                name: '@error/404Template.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Page Not Found',
                    ),
                ],
            ),
        );

        return $response;
    }
}
