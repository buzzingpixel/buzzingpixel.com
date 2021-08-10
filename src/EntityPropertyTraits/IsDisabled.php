<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsDisabled
{
    private bool $isDisabled;

    public function isDisabled(): bool
    {
        return $this->isDisabled;
    }

    public function isNotDisabled(): bool
    {
        return ! $this->isDisabled();
    }

    /**
     * @return $this
     */
    public function withIsDisabled(bool $isDisabled = true): self
    {
        $clone = clone $this;

        $clone->isDisabled = $isDisabled;

        return $clone;
    }
}
