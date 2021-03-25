<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

use function assert;

trait LastTouchedAt
{
    private DateTimeImmutable $lastTouchedAt;

    public function lastTouchedAt(): DateTimeImmutable
    {
        return $this->lastTouchedAt;
    }

    /**
     * @return $this
     */
    public function withLastTouchedAt(
        string | DateTimeInterface $lastTouchedAt
    ): self {
        $clone = clone $this;

        if ($lastTouchedAt instanceof DateTimeInterface) {
            $lastTouchedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastTouchedAt->format(
                    DateTimeInterface::ATOM
                ),
            );
        } else {
            $lastTouchedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastTouchedAt,
            );
        }

        assert($lastTouchedAtClass instanceof DateTimeImmutable);

        $lastTouchedAtClass = $lastTouchedAtClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $clone->lastTouchedAt = $lastTouchedAtClass;

        return $clone;
    }
}
