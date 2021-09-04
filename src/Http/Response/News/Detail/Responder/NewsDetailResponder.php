<?php

declare(strict_types=1);

namespace App\Http\Response\News\Detail\Responder;

use App\Context\Content\Entities\ContentItem;
use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function assert;

class NewsDetailResponder implements NewsDetailResponderContract
{
    public function __construct(
        private TwigEnvironment $twig,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function respond(?ContentItem $newsItem): ResponseInterface
    {
        assert($newsItem instanceof ContentItem);

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            'Http/Content/ContentDetail.twig',
            [
                'meta' => new Meta(
                    metaTitle: $newsItem->title() . ' | News',
                ),
                'breadcrumbSingle' => [
                    'content' => 'News',
                    'uri' => '/news',
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Home',
                        'uri' => '/',
                    ],
                    [
                        'content' => 'News',
                        'uri' => '/news',
                    ],
                    ['content' => 'Viewing Item'],
                ],
                'contentItem' => $newsItem,
            ],
        ));

        return $response;
    }
}
