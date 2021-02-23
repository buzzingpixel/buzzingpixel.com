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
    private ResponseFactoryInterface $responseFactory;
    private TwigEnvironment $twig;
    private SiteUrl $siteUrl;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        TwigEnvironment $twig,
        SiteUrl $siteUrl,
    ) {
        $this->responseFactory = $responseFactory;
        $this->twig            = $twig;
        $this->siteUrl         = $siteUrl;
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
                name: '@home/HomeTemplate.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Specializing in Fine Web Software',
                    ),
                    'ctas' => [
                        [
                            'href' => $this->siteUrl->siteUrl('/software/ansel-ee'),
                            'content' => 'Ansel for EE',
                            'type' => 'primary',
                        ],
                        [
                            'href' => $this->siteUrl->siteUrl('/software/ansel-craft'),
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
