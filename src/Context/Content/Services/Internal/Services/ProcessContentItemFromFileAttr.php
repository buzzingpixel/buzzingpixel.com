<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Services;

use App\Context\Content\Entities\ContentItem;
use App\Context\Content\Services\Internal\Factories\YamlFrontMatterDocumentFactory;
use cebe\markdown\GithubMarkdown;
use DateTimeImmutable;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;

use function assert;
use function is_array;

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

        $matter = $yamlDoc->matter();

        assert(is_array($matter));

        return new ContentItem(
            title: (string) ($matter['title'] ?? ''),
            slug: (string) ($matter['slug'] ?? ''),
            author: (string) ($matter['author'] ?? 'TJ Draper'),
            dateString: (string) (
                $matter['date'] ??
                (new DateTimeImmutable())->format('Y-m-d g:i A')
            ),
            rawBody: $yamlDoc->body(),
            body: $this->markdown->parse($yamlDoc->body()),
        );
    }
}
