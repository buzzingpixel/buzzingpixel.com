<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselEE;

use App\Http\Entities\Meta;
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
        // private SiteUrl $siteUrl,
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
                '@software/AnselEE/AnselEETemplate.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Ansel Image Field Type for ExpressionEngine',
                    ),
                    'navItems' => AnselEEVariables::NAV,
                    'ctas' => [],
                    'postCtaContent' => '',
                    // 'ctas' => [
                    //     [
                    //         'caret' => false,
                    //         'href' => $this->siteUrl->siteUrl('/software/ansel-ee/download'),
                    //         'content' => 'Download Ansel',
                    //         'type' => 'primary',
                    //     ],
                    //     [
                    //         'caret' => false,
                    //         'href' => $this->siteUrl->siteUrl('/software/ansel-ee/purchase'),
                    //         'content' => 'Purchase License ($79)',
                    //         'type' => 'primary',
                    //     ],
                    // ],
                    // 'postCtaContent' => 'Comes with 1 year of support and updates. $39/year thereafter. Can be canceled at any time.',
                ],
            ),
        );

        return $response;
    }
}
