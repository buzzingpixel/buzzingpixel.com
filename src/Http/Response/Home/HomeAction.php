<?php

declare(strict_types=1);

namespace App\Http\Response\Home;

use App\Http\Entities\Meta;
use App\Templating\TwigExtensions\SiteUrl;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeAction
{
    public function __construct(
        private SiteUrl $siteUrl,
        private TwigEnvironment $twig,
        private ResponseFactoryInterface $responseFactory,
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
                '@home/HomeTemplate.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Specializing in Fine Web Software',
                    ),
                    'ctas' => [
                        [
                            'href' => $this->siteUrl->siteUrl(
                                uri: '/software/ansel-ee'
                            ),
                            'content' => 'Ansel for EE',
                            'type' => 'primary',
                        ],
                        [
                            'href' => $this->siteUrl->siteUrl(
                                uri: '/software/ansel-craft'
                            ),
                            'content' => 'Ansel for Craft',
                            'type' => 'primary',
                        ],
                    ],
                ],
            ),
        );

        return $response;
    }
}
