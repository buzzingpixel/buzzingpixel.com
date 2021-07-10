<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripeCaptured
{
    private bool $stripeCaptured = false;

    public function stripeCaptured(): bool
    {
        return $this->stripeCaptured;
    }

    /**
     * @return $this
     */
    public function withStripeCaptured(bool $stripeCaptured): self
    {
        $clone = clone $this;

        $clone->stripeCaptured = $stripeCaptured;

        return $clone;
    }
}
