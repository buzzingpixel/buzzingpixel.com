<?php

declare(strict_types=1);

namespace App\Context\TempFileStorage\Services;

use App\Utilities\SystemClock;
use Config\General;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Safe\Exceptions\DatetimeException;

use function array_walk;
use function pathinfo;

class CleanUploadedFiles
{
    private string $directory;

    public function __construct(
        General $generalConfig,
        private Filesystem $filesystem,
        private SystemClock $clock
    ) {
        $directory = $generalConfig->pathToStorageDirectory();

        $directory .= '/temp';

        $this->directory = $directory;
    }

    /**
     * @throws FilesystemException
     */
    public function clean(): void
    {
        $directories = $this->filesystem->listContents(
            $this->directory,
            false,
        )->toArray();

        array_walk(
            $directories,
            [$this, 'processDirectory'],
        );
    }

    /**
     * @throws FilesystemException
     * @throws DatetimeException
     */
    protected function processDirectory(DirectoryAttributes $attributes): void
    {
        if ($attributes->type() !== 'dir') {
            return;
        }

        $pathInfo = pathinfo($attributes->path());

        $nameTimestamp = (int) ($pathInfo['basename'] ?? 0);

        $twoHoursAgoTimeStamp = $this->clock->getCurrentTime()
            ->modify('- 2 hours')
            ->getTimestamp();

        if ($twoHoursAgoTimeStamp < $nameTimestamp) {
            return;
        }

        $this->filesystem->deleteDirectory(
            $this->directory . '/' . $nameTimestamp,
        );
    }
}
