<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait NewSolutionFileLocation
{
    private string $newSolutionFileLocation;

    public function newSolutionFileLocation(): string
    {
        return $this->newSolutionFileLocation;
    }

    /**
     * @return $this
     */
    public function withNewSolutionFileLocation(
        string $newSolutionFileLocation,
    ): self {
        $clone = clone $this;

        $clone->newSolutionFileLocation = $newSolutionFileLocation;

        return $clone;
    }
}
