<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use function pathinfo;

trait SolutionFile
{
    private string $solutionFile = '';

    public function solutionFile(): string
    {
        return $this->solutionFile;
    }

    /**
     * @return $this
     */
    public function withSolutionFile(string $solutionFile): self
    {
        $clone = clone $this;

        $clone->solutionFile = $solutionFile;

        return $clone;
    }

    public function solutionFileName(): string
    {
        if ($this->solutionFile() === '') {
            return '';
        }

        return pathinfo($this->solutionFile())['basename'];
    }
}
