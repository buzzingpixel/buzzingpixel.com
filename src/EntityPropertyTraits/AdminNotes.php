<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait AdminNotes
{
    private string $adminNotes;

    public function adminNotes(): string
    {
        return $this->adminNotes;
    }

    /**
     * @return $this
     */
    public function withAdminNotes(string $adminNotes): self
    {
        $clone = clone $this;

        $clone->adminNotes = $adminNotes;

        return $clone;
    }
}
