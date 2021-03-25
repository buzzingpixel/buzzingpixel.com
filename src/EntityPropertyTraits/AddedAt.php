<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;

trait AddedAt
{
    private DateTimeImmutable $addedAt;

    public function addedAt(): DateTimeImmutable
    {
        return $this->addedAt;
    }

    /**
     * @return $this
     */
    public function withAddedAt(DateTimeImmutable $addedAt): self
    {
        $clone = clone $this;

        $clone->addedAt = $addedAt;

        return $clone;
    }
}
