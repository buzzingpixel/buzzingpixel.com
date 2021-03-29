<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsSubscription
{
    private bool $isSubscription = false;

    public function isSubscription(): bool
    {
        return $this->isSubscription;
    }

    /**
     * @return $this
     */
    public function withIsSubscription(bool $isSubscription): self
    {
        $clone = clone $this;

        $clone->isSubscription = $isSubscription;

        return $clone;
    }
}
