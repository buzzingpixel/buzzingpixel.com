<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselCraft\Documentation\V1;

use App\Http\Entities\Meta;
use App\Http\Response\Software\AnselCraft\Documentation\AnselCraftDocVariables;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function count;

class AnselCraftV1DocFieldTypeSettingsAction
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
        $response = $this->responseFactory->createResponse()
            ->withHeader('EnableStaticCache', 'true');

        $response->getBody()->write(
            $this->twig->render(
                '@software/AnselCraft/Documentation/V1/AnselCraftV1DocFieldTypeSettings.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Ansel 1.x for Craft Field Type Settings',
                    ),
                    'breadcrumbTrail' => AnselCraftDocVariables::V1_BREADCRUMB_TRAIL,
                    'breadcrumbSingle' => AnselCraftDocVariables::V1_BREADCRUMB_TRAIL[count(AnselCraftDocVariables::V1_BREADCRUMB_TRAIL) - 2],
                    'documentationVersions' => AnselCraftDocVariables::getVersionNav('ansel1'),
                    'documentationPageLinks' => AnselCraftDocVariables::getVersion1Pages('field-type-settings'),
                    'heading' => 'Field Type Settings',
                ],
            ),
        );

        return $response;
    }
}
