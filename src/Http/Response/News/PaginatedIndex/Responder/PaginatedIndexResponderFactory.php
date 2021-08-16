<?php

declare(strict_types=1);

namespace App\Http\Response\News\PaginatedIndex\Responder;

use App\Context\Content\Entities\ContentItemCollection;
use Psr\Http\Message\ServerRequestInterface;

class PaginatedIndexResponderFactory
{
    public function __construct(
        private PaginatedIndexResponderPage1 $responderPage1,
        private PaginatedIndexResponderPage2OrGreater $resonderPage2OrGreater,
    ) {
    }

    /** @phpstan-ignore-next-line  */
    public function getResponderPage1(
        ?int $pageNumber,
        ContentItemCollection $newsCollection,
        ServerRequestInterface $request,
    ): PaginatedIndexResponderContract {
        if ($pageNumber === null || $newsCollection->count() < 1) {
            return new PaginatedIndexResponderInvalid($request);
        }

        if ($pageNumber > 1) {
            return $this->resonderPage2OrGreater;
        }

        return $this->responderPage1;
    }
}
