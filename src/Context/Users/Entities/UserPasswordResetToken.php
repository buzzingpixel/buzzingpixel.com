<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\EntityPropertyTraits\CreatedAt;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\UserId;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Users\UserPasswordResetTokenRecord;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;

use function assert;
use function is_string;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class UserPasswordResetToken
{
    use Id;
    use UserId;
    use CreatedAt;

    public static function fromRecord(UserPasswordResetTokenRecord $record): self
    {
        return new self(
            id: $record->getId()->toString(),
            userId: $record->getUserId()->toString(),
            createdAt: $record->getCreatedAt(),
        );
    }

    public function __construct(
        string $userId,
        null | string | DateTimeInterface $createdAt = null,
        ?string $id = null,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        if ($id === null) {
            $this->id = IdValue::create();
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->userId = IdValue::fromString($userId);

        if ($createdAt instanceof DateTimeInterface) {
            $createdAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $createdAt->format(DateTimeInterface::ATOM),
            );
        } elseif (is_string($createdAt)) {
            $createdAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $createdAt,
            );
        } else {
            $createdAtClass = new DateTimeImmutable();
        }

        assert($createdAtClass instanceof DateTimeImmutable);

        $createdAtClass = $createdAtClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $this->createdAt = $createdAtClass;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;
}
