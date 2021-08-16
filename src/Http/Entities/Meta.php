<?php

declare(strict_types=1);

namespace App\Http\Entities;

use App\Http\Entities\ValueObjects\StringValue;

use function is_string;

class Meta
{
    private StringValue $metaTitle;
    private StringValue $pageHeading;
    private StringValue $pageSubHeading;

    public function __construct(
        ?string $metaTitle = null,
        ?string $pageHeading = null,
        ?string $pageSubHeading = null,
    ) {
        $this->metaTitle = new StringValue(
            value: is_string($metaTitle) ? $metaTitle : '',
        );

        $this->pageHeading = new StringValue(
            value: is_string($pageHeading) ? $pageHeading : '',
        );

        $this->pageSubHeading = new StringValue(
            value: is_string($pageSubHeading) ? $pageSubHeading : '',
        );
    }

    public function getMetaTitle(): StringValue
    {
        return $this->metaTitle;
    }

    public function getPageHeading(): StringValue
    {
        return $this->pageHeading;
    }

    public function getPageSubHeading(): StringValue
    {
        return $this->pageSubHeading;
    }
}
