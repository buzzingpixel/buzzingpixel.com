<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

use function assert;

trait LastCommentAt
{
    private DateTimeImmutable $lastCommentAt;

    public function lastCommentAt(): DateTimeImmutable
    {
        return $this->lastCommentAt;
    }

    /**
     * @return $this
     */
    public function withLastCommentAt(
        string | DateTimeInterface $lastCommentAt
    ): self {
        $clone = clone $this;

        if ($lastCommentAt instanceof DateTimeInterface) {
            $lastCommentAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastCommentAt->format(
                    DateTimeInterface::ATOM
                ),
            );
        } else {
            $lastCommentAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastCommentAt,
            );
        }

        assert($lastCommentAtClass instanceof DateTimeImmutable);

        $lastCommentAtClass = $lastCommentAtClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $clone->lastCommentAt = $lastCommentAtClass;

        return $clone;
    }
}
