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
            ->withHeader('EnableStaticCache', 'true');

        $response->getBody()->write(
            $this->twig->render(
                '@software/Treasury/Treasury.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Treasury for ExpressionEngine',
                    ),
                    'navItems' => TreasuryVariables::NAV,
                    'ctas' => [
                        [
                            'caret' => false,
                            'href' =>  $this->siteUrl->siteUrl('/software/treasury/purchase'),
                            'content' => 'Purchase License ($79)',
                            'type' => 'primary',
                        ],
                    ],
                    'postCtaContent' => 'Comes with 1 year of support and updates. $39/year thereafter. Can be canceled at any time.',
                ],
            ),
        );

        return $response;
    }
}
