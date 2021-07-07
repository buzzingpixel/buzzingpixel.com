<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait UserNotes
{
    private string $userNotes;

    public function userNotes(): string
    {
        return $this->userNotes;
    }

    /**
     * @return $this
     */
    public function withUserNotes(string $userNotes): self
    {
        $clone = clone $this;

        $clone->userNotes = $userNotes;

        return $clone;
    }
}
