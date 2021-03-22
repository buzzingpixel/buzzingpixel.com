<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\EntityValueObjects\Id;
use App\Persistence\Entities\Users\UserSupportProfileRecord;
use LogicException;
use Ramsey\Uuid\UuidInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class UserSupportProfile
{
    private Id $id;
    private string $displayName;

    public static function fromRecord(UserSupportProfileRecord $record): self
    {
        return new self(
            id: $record->getId(),
            displayName: $record->getDisplayName(),
        );
    }

    public function __construct(
        string $displayName = '',
        null | string | UuidInterface $id = null,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        if ($id === null) {
            $this->id = Id::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = Id::fromString($id->toString());
        } else {
            $this->id = Id::fromString($id);
        }

        $this->displayName = $displayName;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function id(): string
    {
        return $this->id->toString();
    }

    public function displayName(): string
    {
        return $this->displayName;
    }

    public function withDisplayName(string $displayName): self
    {
        $clone = clone $this;

        $clone->displayName = $displayName;

        return $clone;
    }
}
