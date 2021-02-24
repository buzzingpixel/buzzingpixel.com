<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselEE\Documentation\V2;

use App\Http\Entities\Meta;
use App\Http\Response\Software\AnselEE\Documentation\AnselEEDocVariables;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function count;

class AnselEEV2DocIndexAction
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
    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse()
            ->withHeader(name: 'EnableStaticCache', value: 'true');

        $response->getBody()->write(
            string: $this->twig->render(
                name: '@software/AnselEE/Documentation/V2/AnselEEV2DocIndex.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Getting Started With Ansel for ExpressionEngine',
                    ),
                    'breadcrumbTrail' => AnselEEDocVariables::V2_BREADCRUMB_TRAIL,
                    'breadcrumbSingle' => AnselEEDocVariables::V2_BREADCRUMB_TRAIL[count(AnselEEDocVariables::V2_BREADCRUMB_TRAIL) - 2],
                    // 'documentationVersions' => AnselEEDocVariables::getVersionNav('ansel2'),
                    'documentationPageLinks' => AnselEEDocVariables::getVersion2Pages('getting-started'),
                    'heading' => 'Getting Started',
                ],
            ),
        );

        return $response;
    }
}
