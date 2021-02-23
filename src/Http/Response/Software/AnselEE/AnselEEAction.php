<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselEE;

use App\Http\Entities\Meta;
use App\Templating\TwigExtensions\SiteUrl;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AnselEEAction
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
                name: '@software/AnselEE/AnselEETemplate.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Ansel Image Field Type for ExpressionEngine',
                    ),
                    'navItems' => AnselEEVariables::NAV,
                    'ctas' => [
                        [
                            'caret' => false,
                            'href' => $this->siteUrl->siteUrl('/software/ansel-ee/download'),
                            'content' => 'Download Ansel',
                            'type' => 'primary',
                        ],
                        [
                            'caret' => false,
                            'href' => $this->siteUrl->siteUrl('/cart/add/ansel-ee'),
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
