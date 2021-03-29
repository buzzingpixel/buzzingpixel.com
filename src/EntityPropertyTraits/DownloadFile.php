<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait DownloadFile
{
    private string $downloadFile;

    public function downloadFile(): string
    {
        return $this->downloadFile;
    }

    /**
     * @return $this
     */
    public function withDownloadFile(string $downloadFile): self
    {
        $clone = clone $this;

        $clone->downloadFile = $downloadFile;

        return $clone;
    }
}
