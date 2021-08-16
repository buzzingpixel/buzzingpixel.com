<?php

declare(strict_types=1);

namespace App\Http\Response\News\PaginatedIndex\Meta;

use App\Http\Entities\Meta;

class MetaFactory
{
    public function getMeta(int $pageNumber): Meta
    {
        if ($pageNumber > 1) {
            return new Meta(
                metaTitle: 'Page  ' . $pageNumber . ' | News',
                pageHeading: 'News, Page ' . $pageNumber,
                pageSubHeading: 'The latest news from BuzzingPixel',
            );
        }

        return new Meta(
            metaTitle: 'News',
            pageHeading: 'News',
            pageSubHeading: 'The latest news from BuzzingPixel',
        );
    }
}
