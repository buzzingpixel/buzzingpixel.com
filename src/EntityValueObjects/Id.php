<?php

declare(strict_types=1);

namespace App\EntityValueObjects;

use App\Persistence\UuidFactoryWithOrderedTimeCodec;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Id
{
    public function __construct(private UuidInterface $id)
    {
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public static function fromString(string $id): self
    {
        return new self(Uuid::fromString($id));
    }

    public static function create(): self
    {
        return new self((new UuidFactoryWithOrderedTimeCodec())->uuid1());
    }
}
