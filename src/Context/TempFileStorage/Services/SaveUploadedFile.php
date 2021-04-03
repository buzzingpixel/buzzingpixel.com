<?php

declare(strict_types=1);

namespace App\Context\TempFileStorage\Services;

use App\Context\TempFileStorage\Entities\TempFile;
use App\Persistence\UuidFactoryWithOrderedTimeCodec;
use App\Utilities\SystemClock;
use Cocur\Slugify\Slugify;
use Config\General;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\UploadedFileInterface;

use function pathinfo;

class SaveUploadedFile
{
    public function __construct(
        private SystemClock $clock,
        private UuidFactoryWithOrderedTimeCodec $uuidFactory,
        private General $config,
        private Filesystem $filesystem,
        private Slugify $slugify,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    public function save(UploadedFileInterface $uploadedFile): TempFile
    {
        $timeStamp = $this->clock->getCurrentTime()->getTimestamp();

        $uuid = $this->uuidFactory->uuid1()->toString();

        $directory = $this->config->pathToStorageDirectory();

        $directory .= '/temp/' . $timeStamp . '/' . $uuid;

        $this->filesystem->createDirectory($directory);

        $pathInfo = pathinfo((string) $uploadedFile->getClientFilename());

        $name = $this->slugify->slugify($pathInfo['filename']);

        if (($pathInfo['extension'] ?? '') !== '') {
            /**
             * @psalm-suppress RedundantCastGivenDocblockType
             * @phpstan-ignore-next-line
             */
            $name .= '.' . (string) ($pathInfo['extension'] ?? '');
        }

        $filePath = $directory . '/' . $name;

        $uploadedFile->moveTo($filePath);

        return new TempFile(
            $timeStamp . '/' . $uuid . '/' . $name,
            $name,
        );
    }
}
