<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\EntityPropertyTraits\CreatedAt;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\LastTouchedAt;
use App\EntityPropertyTraits\UserId;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Users\UserSessionRecord;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;

use function assert;
use function is_string;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class UserSession
{
    use Id;
    use UserId;
    use CreatedAt;
    use LastTouchedAt;

    public static function fromRecord(UserSessionRecord $record): self
    {
        return new self(
            id: $record->getId()->toString(),
            userId: $record->getUserId()->toString(),
            createdAt: $record->getCreatedAt(),
            lastTouchedAt: $record->getLastTouchedAt(),
        );
    }

    public function __construct(
        string $userId,
        null | string | DateTimeInterface $createdAt = null,
        null | string | DateTimeInterface $lastTouchedAt = null,
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

        if ($lastTouchedAt instanceof DateTimeInterface) {
            $lastTouchedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastTouchedAt->format(
                    DateTimeInterface::ATOM
                ),
            );
        } elseif (is_string($lastTouchedAt)) {
            $lastTouchedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastTouchedAt,
            );
        } else {
            $lastTouchedAtClass = new DateTimeImmutable();
        }

        assert($lastTouchedAtClass instanceof DateTimeImmutable);

        $lastTouchedAtClass = $lastTouchedAtClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $this->lastTouchedAt = $lastTouchedAtClass;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;
}
