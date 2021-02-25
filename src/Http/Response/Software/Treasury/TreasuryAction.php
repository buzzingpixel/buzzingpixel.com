<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Treasury;

use App\Http\Entities\Meta;
use App\Templating\TwigExtensions\SiteUrl;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TreasuryAction
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
            ->withHeader(name: 'EnableStaticCache', value: 'true');

        $response->getBody()->write(
            string: $this->twig->render(
                name: '@software/Treasury/Treasury.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Treasury for ExpressionEngine',
                    ),
                    'navItems' => TreasuryVariables::NAV,
                    'ctas' => [
                        [
                            'caret' => false,
                            'href' =>  $this->siteUrl->siteUrl('/cart/add/treasury'),
                            'content' => 'Add To Cart ($79)',
                            'type' => 'primary',
                        ],
                    ],
                ],
            ),
        );

        return $response;
    }
}
