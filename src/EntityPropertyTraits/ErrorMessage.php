<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait ErrorMessage
{
    private ?string $errorMessage;

    public function errorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @return $this
     */
    public function withErrorMessage(?string $errorMessage): self
    {
        $clone = clone $this;

        $clone->errorMessage = $errorMessage;

        return $clone;
    }
}
