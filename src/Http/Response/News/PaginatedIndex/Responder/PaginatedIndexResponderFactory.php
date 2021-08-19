<?php

declare(strict_types=1);

namespace App\Http\Response\News\PaginatedIndex\Responder;

use App\Context\Content\Entities\ContentItemCollection;
use Psr\Http\Message\ServerRequestInterface;

class PaginatedIndexResponderFactory
{
    public function __construct(
        private PaginatedIndexResponderPage1 $responderPage1,
        private PaginatedIndexResponderPage2OrGreater $responderPage2OrGreater,
    ) {
    }

    /** @phpstan-ignore-next-line  */
    public function getResponder(
        ?int $pageNumber,
        ContentItemCollection $newsCollection,
        ServerRequestInterface $request,
    ): PaginatedIndexResponderContract {
        if ($pageNumber === null || $newsCollection->count() < 1) {
            return new PaginatedIndexResponderInvalid($request);
        }

        if ($pageNumber > 1) {
            return $this->responderPage2OrGreater;
        }

        return $this->responderPage1;
    }
}
