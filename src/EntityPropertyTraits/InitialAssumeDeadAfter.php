<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;

trait InitialAssumeDeadAfter
{
    private DateTimeImmutable $initialAssumeDeadAfter;

    public function initialAssumeDeadAfter(): DateTimeImmutable
    {
        return $this->initialAssumeDeadAfter;
    }

    public function withInitialAssumeDeadAfter(
        DateTimeImmutable $initialAssumeDeadAfter,
    ): self {
        $clone = clone $this;

        $clone->initialAssumeDeadAfter = $initialAssumeDeadAfter;

        return $clone;
    }
}
