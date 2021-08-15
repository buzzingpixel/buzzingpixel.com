<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Services;

use App\Context\Cache\Entities\CacheItem;
use App\Context\Content\Entities\ContentItemCollection;
use App\Context\Content\Services\Internal\Contracts\ContentItemsFromDirectory;
use App\Context\Path\Entities\Path;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;
use Psr\Cache\CacheItemPoolInterface;

use function array_map;

class ContentItemsFromDirectoryFetchAndCache implements ContentItemsFromDirectory
{
    public function __construct(
        private Filesystem $filesystem,
        private CacheItemPoolInterface $cacheItemPool,
        private ProcessContentItemFromFileAttr $process,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function get(Path $path, string $cacheKey): ContentItemCollection
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $files = $this->filesystem->listContents($path->toString())
            ->filter(
                static fn (StorageAttributes $a) => $a->isFile() === true
            );

        /** @psalm-suppress MixedArgument */
        $collection = new ContentItemCollection(
            array_map(
                [$this->process, 'process'],
                $files->toArray(),
            ),
        );

        $collection = $collection->sort('dateString', 'desc');

        $this->cacheItemPool->save(new CacheItem(
            key: $cacheKey,
            value: $collection,
        ));

        return $collection;
    }
}
