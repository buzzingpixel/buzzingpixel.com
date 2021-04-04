<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait NewFileLocation
{
    private string $newFileLocation;

    public function newFileLocation(): string
    {
        return $this->newFileLocation;
    }

    /**
     * @return $this
     */
    public function withNewFileLocation(string $newFileLocation): self
    {
        $clone = clone $this;

        $clone->newFileLocation = $newFileLocation;

        return $clone;
    }
}
