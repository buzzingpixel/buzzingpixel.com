<?php

declare(strict_types=1);

namespace App\Http\Entities;

use App\Http\Entities\ValueObjects\MetaTitle;

use function is_string;

class Meta
{
    private MetaTitle $metaTitle;

    public function __construct(
        ?string $metaTitle = null,
    ) {
        $this->metaTitle = new MetaTitle(
            is_string($metaTitle) ? $metaTitle : '',
        );
    }

    public function getMetaTitle(): MetaTitle
    {
        return $this->metaTitle;
    }
}
