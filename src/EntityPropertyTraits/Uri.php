<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Uri
{
    private string $uri;

    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * @return $this
     */
    public function withUri(string $uri): self
    {
        $clone = clone $this;

        $clone->uri = $slug;

        return $clone;
    }
}
