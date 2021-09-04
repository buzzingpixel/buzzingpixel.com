<?php

declare(strict_types=1);

namespace App\Http\Response\Error;

use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Error500Responder
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private LoggerInterface $logger,
        private TwigEnvironment $twig
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Throwable $exception): ResponseInterface
    {
        $this->logger->error(
            'An exception was thrown',
            ['exception' => $exception],
        );

        $response = $this->responseFactory->createResponse(
            500,
            'An internal server error occurred',
        );

        $response->getBody()->write(
            $this->twig->render(
                '@error/500Template.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Internal Server Error',
                    ),
                ],
            )
        );

        return $response;
    }
}
