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
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(
            404,
            'Page not found',
        );

        $response->getBody()->write(
            $this->twig->render(
                '@error/404Template.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Page Not Found',
                    ),
                ],
            ),
        );

        return $response;
    }
}
