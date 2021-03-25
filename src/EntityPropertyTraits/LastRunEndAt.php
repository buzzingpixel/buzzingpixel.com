<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;

trait LastRunEndAt
{
    private ?DateTimeImmutable $lastRunEndAt;

    public function lastRunEndAt(): ?DateTimeImmutable
    {
        return $this->lastRunEndAt;
    }

    /**
     * @return $this
     */
    public function withLastRunEndAt(?DateTimeImmutable $lastRunEndAt): self
    {
        $clone = clone $this;

        $clone->lastRunEndAt = $lastRunEndAt;

        return $clone;
    }
}
