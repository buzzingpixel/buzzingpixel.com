<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsPublic
{
    private bool $isPublic;

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function isNotPublic(): bool
    {
        return ! $this->isPublic;
    }

    public function isPrivate(): bool
    {
        return ! $this->isPublic;
    }

    /**
     * @return $this
     */
    public function withIsPublic(bool $isPublic): self
    {
        $clone = clone $this;

        $clone->isPublic = $isPublic;

        return $clone;
    }
}
