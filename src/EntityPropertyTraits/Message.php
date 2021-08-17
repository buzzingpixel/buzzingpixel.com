<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Message
{
    private string $message;

    public function message(): string
    {
        return $this->message;
    }

    /**
     * @return $this
     */
    public function withMessage(string $message): self
    {
        $clone = clone $this;

        $clone->message = $message;

        return $clone;
    }
}
