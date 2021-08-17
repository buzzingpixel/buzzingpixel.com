<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use function pathinfo;

trait LegacySolutionFile
{
    private string $legacySolutionFile = '';

    public function legacySolutionFile(): string
    {
        return $this->legacySolutionFile;
    }

    /**
     * @return $this
     */
    public function withLegacySolutionFile(string $legacySolutionFile): self
    {
        $clone = clone $this;

        $clone->legacySolutionFile = $legacySolutionFile;

        return $clone;
    }

    public function legacySolutionFileName(): string
    {
        if ($this->legacySolutionFile() === '') {
            return '';
        }

        return pathinfo($this->legacySolutionFile())['basename'];
    }
}
