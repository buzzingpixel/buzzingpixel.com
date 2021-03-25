<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\EntityPropertyTraits\DisplayName;
use App\EntityPropertyTraits\Id;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Users\UserSupportProfileRecord;
use LogicException;
use Ramsey\Uuid\UuidInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class UserSupportProfile
{
    use Id;
    use DisplayName;

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
            $this->id = IdValue::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = IdValue::fromString($id->toString());
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->displayName = $displayName;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;
}
