<?php

declare(strict_types=1);

namespace App\Http\Response\News\PaginatedIndex;

use App\Context\Content\ContentApi;
use App\Context\Path\PathApi;
use App\Http\Entities\Pagination;
use App\Http\Response\News\PaginatedIndex\Meta\MetaFactory;
use App\Http\Response\News\PaginatedIndex\Responder\PaginatedIndexResponderFactory;
use App\Http\Utilities\General\PageNumberUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PaginatedIndexAction
{
    private const PER_PAGE = 12;

    public function __construct(
        private PathApi $pathApi,
        private ContentApi $contentApi,
        private MetaFactory $metaFactory,
        private PageNumberUtil $pageNumberUtil,
        private PaginatedIndexResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $pageNumString = (string) $request->getAttribute('pageNum');

        $pageNumber = $this->pageNumberUtil->pageNumberFromString(
            incoming: $pageNumString,
        );

        $pageNumberOrNull = $this->pageNumberUtil->pageNumberOrNullFromString(
            incoming: $pageNumString,
        );

        $newsCollectionTotal = $this->contentApi->contentItemsFromDirectory(
            path: $this->pathApi->pathFromProjectRoot('content/news'),
        );

        $newsCollection = $newsCollectionTotal->slice(
            offset: ($pageNumber * self::PER_PAGE) - self::PER_PAGE,
            length: self::PER_PAGE,
        );

        $pagination = (new Pagination())
            ->withBase(val: '/news')
            ->withCurrentPage(val: $pageNumber)
            ->withPerPage(val: self::PER_PAGE)
            ->withTotalResults(val: $newsCollectionTotal->count());

        return $this->responderFactory->getResponder(
            pageNumber: $pageNumberOrNull,
            newsCollection: $newsCollection,
            request: $request,
        )->respond(
            newsCollection: $newsCollection,
            pagination: $pagination,
            meta: $this->metaFactory->getMeta(pageNumber: $pageNumber),
        );
    }
}
