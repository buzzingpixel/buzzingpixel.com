<?php

declare(strict_types=1);

namespace App\Content\Changelog;

class ParseChangelogFromMarkdownFile
{
    public function parse(string $location): ChangelogPayload
    {
        $reader = new Reader($location);

        return new ChangelogPayload($reader->getReleases());
    }
}
