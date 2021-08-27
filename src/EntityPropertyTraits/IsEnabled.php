<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsEnabled
{
    private bool $isEnabled;

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function isNotEnabled(): bool
    {
        return ! $this->isEnabled;
    }

    /**
     * @return $this
     */
    public function withIsEnabled(bool $isEnabled): self
    {
        $clone = clone $this;

        $clone->isEnabled = $isEnabled;

        return $clone;
    }
}
