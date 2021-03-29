<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

use function assert;

trait ReleasedOn
{
    private DateTimeImmutable $releasedOn;

    public function releasedOn(): DateTimeImmutable
    {
        return $this->releasedOn;
    }

    /**
     * @return $this
     */
    public function withReleasedOn(string | DateTimeInterface $releasedOn): self
    {
        $clone = clone $this;

        if ($releasedOn instanceof DateTimeInterface) {
            $releasedOnClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $releasedOn->format(DateTimeInterface::ATOM),
            );
        } else {
            $releasedOnClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $releasedOn,
            );
        }

        assert($releasedOnClass instanceof DateTimeImmutable);

        $createdAtClass = $releasedOnClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $clone->releasedOn = $createdAtClass;

        return $clone;
    }
}
