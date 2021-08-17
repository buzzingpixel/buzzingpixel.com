<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Status
{
    private string $status;

    public function status(): string
    {
        return $this->status;
    }

    /**
     * @return $this
     */
    public function withStatus(string $status): self
    {
        $clone = clone $this;

        $clone->status = $status;

        return $clone;
    }
}
