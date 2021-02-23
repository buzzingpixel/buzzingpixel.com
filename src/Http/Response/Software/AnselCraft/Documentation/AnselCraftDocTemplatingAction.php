<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselCraft\Documentation;

use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function count;

class AnselCraftDocTemplatingAction
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
                '@software/AnselCraft/Documentation/AnselCraftDocTemplating.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Getting Started With Ansel for Craft',
                    ),
                    'breadcrumbTrail' => AnselCraftDocVariables::BREADCRUMB_TRAIL,
                    'breadcrumbSingle' => AnselCraftDocVariables::BREADCRUMB_TRAIL[count(AnselCraftDocVariables::BREADCRUMB_TRAIL) - 2],
                    'documentationVersions' => AnselCraftDocVariables::getVersionNav('ansel2'),
                    'documentationPageLinks' => AnselCraftDocVariables::getVersion2Pages('templating'),
                    'heading' => 'Getting Started',
                ]
            )
        );

        return $response;
    }
}
