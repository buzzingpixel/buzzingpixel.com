<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Construct;

use App\Http\Entities\Meta;
use App\Templating\TwigExtensions\SiteUrl;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ConstructAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private SiteUrl $siteUrl,
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
                '@software/Construct/Construct.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Construct for ExpressionEngine',
                    ),
                    'navItems' => ConstructVariables::NAV,
                    'ctas' => [
                        [
                            'caret' => false,
                            'href' =>  $this->siteUrl->siteUrl('/software/construct/purchase'),
                            'content' => 'Purchase License ($40)',
                            'type' => 'primary',
                        ],
                    ],
                    'postCtaContent' => 'Comes with 1 year of support and updates. $20/year thereafter. Can be canceled at any time.',
                ],
            ),
        );

        return $response;
    }
}
