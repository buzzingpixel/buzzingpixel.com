<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselCraft\Documentation\V2;

use App\Http\Entities\Meta;
use App\Http\Response\Software\AnselCraft\Documentation\AnselCraftDocVariables;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function count;

class AnselCraftDocFieldTypeUseAction
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
        $response = $this->responseFactory->createResponse()
            ->withHeader('EnableStaticCache', 'true');

        $response->getBody()->write(
            $this->twig->render(
                '@software/AnselCraft/Documentation/V2/AnselCraftDocFieldTypeUse.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Ansel for Craft Field Type Settings',
                    ),
                    'breadcrumbTrail' => AnselCraftDocVariables::V2_BREADCRUMB_TRAIL,
                    'breadcrumbSingle' => AnselCraftDocVariables::V2_BREADCRUMB_TRAIL[count(AnselCraftDocVariables::V2_BREADCRUMB_TRAIL) - 2],
                    'documentationVersions' => AnselCraftDocVariables::getVersionNav('ansel2'),
                    'documentationPageLinks' => AnselCraftDocVariables::getVersion2Pages('field-type-use'),
                    'heading' => 'Field Type Settings',
                ]
            )
        );

        return $response;
    }
}
