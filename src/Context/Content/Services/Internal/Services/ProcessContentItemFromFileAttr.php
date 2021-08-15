<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Services;

use App\Context\Content\Entities\ContentItem;
use App\Context\Content\Services\Internal\Factories\YamlFrontMatterDocumentFactory;
use cebe\markdown\GithubMarkdown;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;

class ProcessContentItemFromFileAttr
{
    public function __construct(
        private Filesystem $filesystem,
        private GithubMarkdown $markdown,
        private YamlFrontMatterDocumentFactory $documentFactory,
    ) {
    }

    public function process(FileAttributes $file): ContentItem
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $contents = $this->filesystem->read($file->path());

        $yamlDoc = $this->documentFactory->createFromString($contents);

        return new ContentItem(
            title: (string) $yamlDoc->matter()['title'],
            slug: (string) $yamlDoc->matter()['slug'],
            dateString: (string) $yamlDoc->matter()['date'],
            rawBody: $yamlDoc->body(),
            body: $this->markdown->parse($yamlDoc->body()),
        );
    }
}
