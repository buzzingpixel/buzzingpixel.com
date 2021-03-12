<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\EntityValueObjects\Id;
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
    private Id $id;
    private Id $userId;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $lastTouchedAt;

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

        if ($lastTouchedAt instanceof DateTimeInterface) {
            $lastTouchedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastTouchedAt->format(DateTimeInterface::ATOM),
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

    public function lastTouchedAt(): DateTimeImmutable
    {
        return $this->lastTouchedAt;
    }

    public function withLastTouchedAt(string | DateTimeInterface $lastTouchedAt): self
    {
        $clone = clone $this;

        if ($lastTouchedAt instanceof DateTimeInterface) {
            $lastTouchedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastTouchedAt->format(DateTimeInterface::ATOM),
            );
        } else {
            $lastTouchedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastTouchedAt,
            );
        }

        assert($lastTouchedAtClass instanceof DateTimeImmutable);

        $lastTouchedAtClass = $lastTouchedAtClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $clone->lastTouchedAt = $lastTouchedAtClass;

        return $clone;
    }
}
