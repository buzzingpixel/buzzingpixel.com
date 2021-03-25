<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait DisplayName
{
    private string $displayName;

    public function displayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return $this
     */
    public function withDisplayName(string $displayName): self
    {
        $clone = clone $this;

        $clone->displayName = $displayName;

        return $clone;
    }
}
