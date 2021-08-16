<?php

declare(strict_types=1);

namespace App\Http\Response\News\Detail;

use App\Context\Content\ContentApi;
use App\Context\Path\PathApi;
use App\Http\Response\News\Detail\Responder\NewsDetailResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NewsDetailAction
{
    public function __construct(
        private PathApi $pathApi,
        private ContentApi $contentApi,
        private NewsDetailResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $slug = (string) $request->getAttribute('slug');

        $newsItem = $this->contentApi->contentItemsFromDirectory(
            path: $this->pathApi->pathFromProjectRoot('content/news'),
        )
            ->where('slug', $slug)
            ->firstOrNull();

        return $this->responderFactory->getResponder(
            slug: $slug,
            newsItem: $newsItem,
            request: $request,
        )->respond(newsItem: $newsItem);
    }
}
