<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Factories;

use Spatie\YamlFrontMatter\Document;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class YamlFrontMatterDocumentFactory
{
    public function createFromString(string $content): Document
    {
        return YamlFrontMatter::parse($content);
    }
}
