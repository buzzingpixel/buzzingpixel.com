<?php

declare(strict_types=1);

namespace App\Http\Response\Software\CategoryConstruct\Documentation\V2;

use App\Http\Entities\Meta;
use App\Http\Response\Software\CategoryConstruct\Documentation\CategoryConstructDocVariables;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function count;

class CategoryConstructV2DocIndexAction
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
            ->withHeader('EnableStaticCache', 'true');

        $response->getBody()->write(
            $this->twig->render(
                '@software/CategoryConstruct/Documentation/V2/CategoryConstructV2DocIndex.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Getting Started With Category Construct',
                    ),
                    'breadcrumbTrail' => CategoryConstructDocVariables::V2_BREADCRUMB_TRAIL,
                    'breadcrumbSingle' => CategoryConstructDocVariables::V2_BREADCRUMB_TRAIL[count(CategoryConstructDocVariables::V2_BREADCRUMB_TRAIL) - 2],
                    'documentationPageLinks' => CategoryConstructDocVariables::getVersion2Pages('getting-started'),
                    'heading' => 'Getting Started',
                ],
            ),
        );

        return $response;
    }
}
