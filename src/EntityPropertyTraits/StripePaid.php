<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripePaid
{
    private bool $stripePaid = false;

    public function stripePaid(): bool
    {
        return $this->stripePaid;
    }

    /**
     * @return $this
     */
    public function withStripePaid(bool $stripePaid): self
    {
        $clone = clone $this;

        $clone->stripePaid = $stripePaid;

        return $clone;
    }
}
