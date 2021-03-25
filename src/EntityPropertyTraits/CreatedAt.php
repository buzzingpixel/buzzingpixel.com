<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

use function assert;

trait CreatedAt
{
    private DateTimeImmutable $createdAt;

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return $this
     */
    public function withCreatedAt(string | DateTimeInterface $createdAt): self
    {
        $clone = clone $this;

        if ($createdAt instanceof DateTimeInterface) {
            $createdAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $createdAt->format(DateTimeInterface::ATOM),
            );
        } else {
            $createdAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $createdAt,
            );
        }

        assert($createdAtClass instanceof DateTimeImmutable);

        $createdAtClass = $createdAtClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $clone->createdAt = $createdAtClass;

        return $clone;
    }
}
