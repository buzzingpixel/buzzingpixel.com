<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselCraft;

use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AnselCraftAction
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
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write(
            $this->twig->render(
                '@software/AnselCraft/Index.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Ansel Image Field Type for Craft CMS',
                    ),
                ]
            )
        );

        return $response;
    }
}
