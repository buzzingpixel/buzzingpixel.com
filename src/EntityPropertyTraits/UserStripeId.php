<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait UserStripeId
{
    private string $userStripeId;

    public function userStripeId(): string
    {
        return $this->userStripeId;
    }

    /**
     * @return $this
     */
    public function withUserStripeId(string $userStripeId): self
    {
        $clone = clone $this;

        $clone->userStripeId = $userStripeId;

        return $clone;
    }
}
