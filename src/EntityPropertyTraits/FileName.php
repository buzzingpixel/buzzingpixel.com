<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait FileName
{
    private string $fileName;

    public function fileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return $this
     */
    public function withFileName(string $fileName): self
    {
        $clone = clone $this;

        $clone->fileName = $fileName;

        return $clone;
    }
}
