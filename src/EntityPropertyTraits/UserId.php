<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\EntityValueObjects\Id as IdValue;

trait UserId
{
    private IdValue $userId;

    public function userId(): string
    {
        return $this->userId->toString();
    }

    /**
     * @return $this
     */
    public function withUserId(string $userId): self
    {
        $clone = clone $this;

        $clone->userId = IdValue::fromString($userId);

        return $clone;
    }
}
