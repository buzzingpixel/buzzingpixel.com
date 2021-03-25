<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;

trait AssumeDeadAfter
{
    private DateTimeImmutable $assumeDeadAfter;

    public function assumeDeadAfter(): DateTimeImmutable
    {
        return $this->assumeDeadAfter;
    }

    /**
     * @return $this
     */
    public function withAssumeDeadAfter(
        DateTimeImmutable $assumeDeadAfter,
    ): self {
        $clone = clone $this;

        $clone->assumeDeadAfter = $assumeDeadAfter;

        return $clone;
    }
}
