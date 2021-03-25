<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\EntityValueObjects\Id as IdValue;
use Ramsey\Uuid\UuidInterface;

trait Id
{
    private IdValue $id;

    public function id(): string
    {
        return $this->id->toString();
    }

    /**
     * @return $this
     */
    public function withId(string | UuidInterface $id): self
    {
        $clone = clone $this;

        if ($id instanceof UuidInterface) {
            $clone->id = IdValue::fromString($id->toString());
        } else {
            $clone->id = IdValue::fromString($id);
        }

        return $clone;
    }
}
