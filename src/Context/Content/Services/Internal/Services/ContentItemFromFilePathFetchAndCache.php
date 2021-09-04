<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Services;

use App\Context\Cache\Entities\CacheItem;
use App\Context\Content\Entities\ContentItem;
use App\Context\Content\Services\Internal\Contracts\ContentItemFromFilePath;
use App\Context\Path\Entities\Path;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Psr\Cache\CacheItemPoolInterface;

class ContentItemFromFilePathFetchAndCache implements ContentItemFromFilePath
{
    public function __construct(
        private Filesystem $filesystem,
        private CacheItemPoolInterface $cacheItemPool,
        private ProcessContentItemFromFileAttr $contentFromFileAttr,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    public function get(Path $path, string $cacheKey): ContentItem
    {
        $fileAttributes = new FileAttributes(
            $path->toString(),
            $this->filesystem->fileSize($path->toString()),
            $this->filesystem->visibility($path->toString()),
            $this->filesystem->lastModified(
                $path->toString()
            ),
            $this->filesystem->mimeType(
                $path->toString(),
            ),
        );

        $contentItem = $this->contentFromFileAttr->process(file: $fileAttributes);

        $this->cacheItemPool->save(new CacheItem(
            key: $cacheKey,
            value: $contentItem,
        ));

        return $contentItem;
    }
}
