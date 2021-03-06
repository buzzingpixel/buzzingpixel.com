<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\EntityValueObjects\EmailAddress;
use App\EntityValueObjects\Id;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;

use function assert;
use function is_string;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class UserEntity
{
    private Id $id;
    private bool $isAdmin;
    private EmailAddress $emailAddress;
    private string $passwordHash;
    private bool $isActive;
    private DateTimeZone $timezone;
    private DateTimeImmutable $createdAt;

    public function __construct(
        bool $isAdmin,
        string $emailAddress,
        string $passwordHash,
        bool $isActive,
        string | DateTimeZone $timezone,
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

        $this->isAdmin = $isAdmin;

        $this->emailAddress = EmailAddress::fromString(
            $emailAddress
        );

        $this->passwordHash = $passwordHash;

        $this->isActive = $isActive;

        if ($timezone instanceof DateTimeZone) {
            $this->timezone = $timezone;
        } else {
            $this->timezone = new DateTimeZone($timezone);
        }

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

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function withIsAdmin(bool $isAdmin): self
    {
        $clone = clone $this;

        $clone->isAdmin = $isAdmin;

        return $clone;
    }

    public function emailAddress(): string
    {
        return $this->emailAddress->toString();
    }

    public function withEmailAddress(string $emailAddress): self
    {
        $clone = clone $this;

        $clone->emailAddress = EmailAddress::fromString(
            $emailAddress
        );

        return $clone;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function withPasswordHash(string $passwordHash): self
    {
        $clone = clone $this;

        $clone->passwordHash = $passwordHash;

        return $clone;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function withIsActive(bool $isActive): self
    {
        $clone = clone $this;

        $clone->isActive = $isActive;

        return $clone;
    }

    public function timezone(): DateTimeZone
    {
        return $this->timezone;
    }

    public function withTimezone(string | DateTimeZone $timezone): self
    {
        $clone = clone $this;

        if ($timezone instanceof DateTimeZone) {
            $clone->timezone = $timezone;
        } else {
            $clone->timezone = new DateTimeZone($timezone);
        }

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
