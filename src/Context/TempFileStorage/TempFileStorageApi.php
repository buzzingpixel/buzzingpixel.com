<?php

declare(strict_types=1);

namespace App\Context\TempFileStorage;

use App\Context\TempFileStorage\Entities\TempFile;
use App\Context\TempFileStorage\Services\SaveUploadedFile;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\UploadedFileInterface;

class TempFileStorageApi
{
    public function __construct(
        private SaveUploadedFile $saveUploadedFile,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    public function saveUploadedFile(UploadedFileInterface $file): TempFile
    {
        return $this->saveUploadedFile->save($file);
    }
}
