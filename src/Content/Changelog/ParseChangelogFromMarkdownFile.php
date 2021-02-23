<?php

declare(strict_types=1);

namespace App\Content\Changelog;

class ParseChangelogFromMarkdownFile
{
    public function parse(string $location): ChangelogPayload
    {
        $reader = new Reader(filename: $location);

        return new ChangelogPayload(releases: $reader->getReleases());
    }
}
