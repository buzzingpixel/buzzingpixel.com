<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Construct\Documentation\V2;

use App\Http\Entities\Meta;
use App\Http\Response\Software\Construct\Documentation\ConstructDocVariables;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function count;

class ConstructV2ExtensionHookAction
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
                '@software/Construct/Documentation/V2/ConstructV2ExtensionHook.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Construct Extension Hooks',
                    ),
                    'breadcrumbTrail' => ConstructDocVariables::V2_BREADCRUMB_TRAIL,
                    'breadcrumbSingle' => ConstructDocVariables::V2_BREADCRUMB_TRAIL[count(ConstructDocVariables::V2_BREADCRUMB_TRAIL) - 2],
                    'documentationPageLinks' => ConstructDocVariables::getVersion2Pages('extension-hooks'),
                    'heading' => 'Construct Extension Hooks',
                ],
            ),
        );

        return $response;
    }
}
