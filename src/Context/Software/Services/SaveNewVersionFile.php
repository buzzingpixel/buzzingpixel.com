<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareVersion;
use Config\General;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use LogicException;

use function assert;
use function pathinfo;

class SaveNewVersionFile
{
    public function __construct(
        private General $config,
        private Filesystem $filesystem,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    public function save(SoftwareVersion $version): SoftwareVersion
    {
        $software = $version->software();

        assert($software instanceof Software);

        $storageDir = $this->config->pathToStorageDirectory();

        $tempDir = $storageDir . '/temp';

        $newFileLocation = $tempDir . '/' . $version->newFileLocation();

        $newFilePathInfo = pathinfo($version->newFileLocation());

        $ext = $newFilePathInfo['extension'] ?? '';

        if (! $this->filesystem->fileExists($newFileLocation)) {
            throw new LogicException(
                'New version download file does not exist'
            );
        }

        $downloadsDir = $storageDir . '/softwareDownloads';

        $targetPath = $downloadsDir . '/' . $software->id() . '/' . $version->id();

        $this->filesystem->createDirectory($targetPath);

        $targetFileName = $software->slug() . '-' . $version->version() . '.' . $ext;

        $targetFullPath = $targetPath . '/' . $targetFileName;

        if ($this->filesystem->fileExists($targetFullPath)) {
            $this->filesystem->delete($targetFullPath);
        }

        $this->filesystem->copy(
            $newFileLocation,
            $targetFullPath,
        );

        return $version->withDownloadFile(
            $software->id() . '/' . $version->id() . '/' . $targetFileName
        );
    }
}
