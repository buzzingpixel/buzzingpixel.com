<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;

trait LastRunStartAt
{
    private ?DateTimeImmutable $lastRunStartAt;

    public function lastRunStartAt(): ?DateTimeImmutable
    {
        return $this->lastRunStartAt;
    }

    /**
     * @return $this
     */
    public function withLastRunStartAt(?DateTimeImmutable $lastRunStartAt): self
    {
        $clone = clone $this;

        $clone->lastRunStartAt = $lastRunStartAt;

        return $clone;
    }
}
