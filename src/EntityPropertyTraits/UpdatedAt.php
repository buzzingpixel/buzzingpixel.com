<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

use function assert;

trait UpdatedAt
{
    private DateTimeImmutable $updatedAt;

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return $this
     */
    public function withUpdatedNow(): self
    {
        return $this->withUpdatedAt(
            updatedAt: (new DateTimeImmutable())
                ->setTimezone(new DateTimeZone('UTC')),
        );
    }

    /**
     * @return $this
     */
    public function withUpdatedAt(string | DateTimeInterface $updatedAt): self
    {
        $clone = clone $this;

        if ($updatedAt instanceof DateTimeInterface) {
            $updatedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $updatedAt->format(DateTimeInterface::ATOM),
            );
        } else {
            $updatedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $updatedAt,
            );
        }

        assert($updatedAtClass instanceof DateTimeImmutable);

        $updatedAtClass = $updatedAtClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $clone->updatedAt = $updatedAtClass;

        return $clone;
    }
}
