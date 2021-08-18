<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait ShortDescription
{
    private string $shortDescription;

    public function shortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * @return $this
     */
    public function withShortDescription(string $shortDescription): self
    {
        $clone = clone $this;

        $clone->shortDescription = $shortDescription;

        return $clone;
    }
}
