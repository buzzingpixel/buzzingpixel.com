<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Treasury\Documentation\V1;

use App\Http\Entities\Meta;
use App\Http\Response\Software\Treasury\Documentation\TreasuryDocVariables;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function count;

class TreasuryV1DocIndexAction
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
                name: '@software/Treasury/Documentation/V1/TreasuryV1DocIndex.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Getting Started With Treasury',
                    ),
                    'breadcrumbTrail' => TreasuryDocVariables::V1_BREADCRUMB_TRAIL,
                    'breadcrumbSingle' => TreasuryDocVariables::V1_BREADCRUMB_TRAIL[count(TreasuryDocVariables::V1_BREADCRUMB_TRAIL) - 2],
                    'documentationPageLinks' => TreasuryDocVariables::getVersion1Pages('getting-started'),
                    'heading' => 'Getting Started',
                ],
            ),
        );

        return $response;
    }
}
