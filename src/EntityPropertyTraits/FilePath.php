<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait FilePath
{
    private string $filePath;

    public function filePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return $this
     */
    public function withFilePath(string $filePath): self
    {
        $clone = clone $this;

        $clone->filePath = $filePath;

        return $clone;
    }
}
