<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait LoggedInOnPageLoad
{
    private bool $loggedInOnPageLoad;

    public function loggedInOnPageLoad(): bool
    {
        return $this->loggedInOnPageLoad;
    }

    /**
     * @return $this
     */
    public function withLoggedInOnPageLoad(bool $loggedInOnPageLoad): self
    {
        $clone = clone $this;

        $clone->loggedInOnPageLoad = $loggedInOnPageLoad;

        return $clone;
    }
}
