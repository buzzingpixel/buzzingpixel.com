<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;

trait Date
{
    private DateTimeImmutable $date;

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function withDate(DateTimeImmutable $date): self
    {
        $clone = clone $this;

        $clone->date = $date;

        return $clone;
    }
}
