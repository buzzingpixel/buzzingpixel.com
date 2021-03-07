<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\EntityValueObjects\Id;
use App\Persistence\Entities\Users\UserPasswordResetTokenRecord;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;

use function assert;
use function is_string;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class UserPasswordResetTokenEntity
{
    private Id $id;
    private Id $userId;
    private DateTimeImmutable $createdAt;

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
            $this->id = Id::create();
        } else {
            $this->id = Id::fromString($id);
        }

        $this->userId = Id::fromString($userId);

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

    public function id(): string
    {
        return $this->id->toString();
    }

    public function userId(): string
    {
        return $this->userId->toString();
    }

    public function withUserId(string $userId): self
    {
        $clone = clone $this;

        $clone->userId = Id::fromString($userId);

        return $clone;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function withCreatedAt(string | DateTimeInterface $createdAt): self
    {
        $clone = clone $this;

        if ($createdAt instanceof DateTimeInterface) {
            $createdAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $createdAt->format(DateTimeInterface::ATOM),
            );
        } else {
            $createdAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $createdAt,
            );
        }

        assert($createdAtClass instanceof DateTimeImmutable);

        $createdAtClass = $createdAtClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $clone->createdAt = $createdAtClass;

        return $clone;
    }
}
