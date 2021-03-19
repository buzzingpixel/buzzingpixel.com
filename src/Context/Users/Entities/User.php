<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\Context\Users\Exceptions\InvalidPassword;
use App\EntityValueObjects\EmailAddress;
use App\EntityValueObjects\Id;
use App\Persistence\Entities\Users\UserRecord;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;

use function assert;
use function is_string;
use function mb_strlen;
use function password_hash;
use function preg_match;

use const PASSWORD_DEFAULT;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class User
{
    private Id $id;
    private bool $isAdmin;
    private EmailAddress $emailAddress;
    private string $passwordHash;
    private bool $isActive;
    private DateTimeZone $timezone;
    private DateTimeImmutable $createdAt;

    public static function fromRecord(UserRecord $record): self
    {
        return new self(
            id: $record->getId()->toString(),
            isAdmin: $record->getIsAdmin(),
            emailAddress: $record->getEmailAddress(),
            passwordHash: $record->getPasswordHash(),
            isActive: $record->getIsActive(),
            timezone: $record->getTimezone(),
            createdAt: $record->getCreatedAt(),
        );
    }

    /**
     * @throws InvalidEmailAddress
     * @throws InvalidPassword
     */
    public function __construct(
        string $emailAddress,
        string $passwordHash = '',
        bool $isAdmin = false,
        bool $isActive = true,
        null | string | DateTimeZone $timezone = null,
        null | string | DateTimeInterface $createdAt = null,
        ?string $id = null,
        string $plainTextPassword = '',
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

        if ($plainTextPassword !== '') {
            $this->validatePassword($plainTextPassword);

            /** @phpstan-ignore-next-line */
            $this->passwordHash = (string) password_hash(
                $plainTextPassword,
                PASSWORD_DEFAULT,
            );
        } elseif ($passwordHash !== '') {
            $this->passwordHash = $passwordHash;
        }

        $this->isActive = $isActive;

        if ($timezone === null) {
            $this->timezone = new DateTimeZone('US/Central');
        } elseif ($timezone instanceof DateTimeZone) {
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

    /**
     * @throws InvalidPassword
     */
    private function validatePassword(string $password): void
    {
        $uppercase    = preg_match('@[A-Z]@', $password);
        $lowercase    = preg_match('@[a-z]@', $password);
        $number       = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (
            $uppercase > 0 &&
            $lowercase > 0 &&
            $number > 0 &&
            $specialChars > 0 &&
            mb_strlen($password) > 7
        ) {
            return;
        }

        throw new InvalidPassword();
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

    /**
     * @throws InvalidPassword
     */
    public function withPassword(string $password): self
    {
        $this->validatePassword($password);

        /** @phpstan-ignore-next-line */
        return $this->withPasswordHash((string) password_hash(
            $password,
            PASSWORD_DEFAULT,
        ));
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
