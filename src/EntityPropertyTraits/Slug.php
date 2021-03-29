<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Slug
{
    private string $slug;

    public function slug(): string
    {
        return $this->slug;
    }

    /**
     * @return $this
     */
    public function withSlug(string $slug): self
    {
        $clone = clone $this;

        $clone->slug = $slug;

        return $clone;
    }
}
