<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait LastCommentAt
{
    /**
     * @Mapping\Column(
     *     name="last_comment_at",
     *     type="datetimetz_immutable",
     *     nullable=false
     * )
     */
    protected DateTimeImmutable $lastCommentAt;

    public function getLastCommentAt(): DateTimeImmutable
    {
        return $this->lastCommentAt;
    }

    public function setLastCommentAt(DateTimeImmutable $lastCommentAt): void
    {
        $this->lastCommentAt = $lastCommentAt;
    }
}
