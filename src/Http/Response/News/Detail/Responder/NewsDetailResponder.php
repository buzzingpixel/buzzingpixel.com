<?php

declare(strict_types=1);

namespace App\Http\Response\News\Detail\Responder;

use App\Context\Content\Entities\ContentItem;
use App\Http\Entities\Meta;
use App\Http\Response\News\ItemLinkResolver;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;

use function assert;

class NewsDetailResponder implements NewsDetailResponderContract
{
    public function __construct(
        private TwigEnvironment $twig,
        private ItemLinkResolver $itemLinkResolver,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

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
                'itemLinkResolver' => $this->itemLinkResolver,
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
