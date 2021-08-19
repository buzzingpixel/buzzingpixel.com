<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing\Factories;

use App\Http\Entities\Meta;

class MetaFactory
{
    public function getMeta(
        int $pageNumber,
        string $baseTitle,
    ): Meta {
        if ($pageNumber > 1) {
            return new Meta(
                metaTitle: 'Page ' . $pageNumber . ' | ' . $baseTitle,
                pageHeading: $baseTitle . ', Page' . $pageNumber,
            );
        }

        return new Meta(
            metaTitle: $baseTitle,
            pageHeading: $baseTitle,
        );
    }
}
