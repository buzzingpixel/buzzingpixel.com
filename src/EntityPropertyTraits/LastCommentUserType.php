<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait LastCommentUserType
{
    private string $lastCommentUserType;

    public function lastCommentUserType(): string
    {
        return $this->lastCommentUserType;
    }

    /**
     * @return $this
     */
    public function withLastCommentUserType(string $lastCommentUserType): self
    {
        $clone = clone $this;

        $clone->lastCommentUserType = $lastCommentUserType;

        return $clone;
    }
}
