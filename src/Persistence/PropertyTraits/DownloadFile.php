<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait DownloadFile
{
    /**
     * @Mapping\Column(
     *     name="download_file",
     *     type="string",
     * )
     */
    protected string $downloadFile = '';

    public function getDownloadFile(): string
    {
        return $this->downloadFile;
    }

    public function setDownloadFile(string $downloadFile): void
    {
        $this->downloadFile = $downloadFile;
    }
}
