<?php

declare(strict_types=1);

namespace App\Http\Response\Software\CategoryConstruct;

use App\Http\Entities\Meta;
use App\Templating\TwigExtensions\SiteUrl;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CategoryConstructAction
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
                '@software/CategoryConstruct/CategoryConstruct.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Category Construct for ExpressionEngine',
                    ),
                    'navItems' => CategoryConstructVariables::NAV,
                    'ctas' => [
                        [
                            'caret' => false,
                            'href' =>  $this->siteUrl->siteUrl('/software/category-construct/purchase'),
                            'content' => 'Purchase License ($15)',
                            'type' => 'primary',
                        ],
                    ],
                ],
            ),
        );

        return $response;
    }
}
