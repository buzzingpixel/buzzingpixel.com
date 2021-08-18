<?php

declare(strict_types=1);

namespace App\Context\Issues\Entities;

use App\Context\Users\Entities\User as UserEntity;
use App\EntityPropertyTraits\CreatedAt;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\Message;
use App\EntityPropertyTraits\UpdatedAt;
use App\EntityPropertyTraits\User;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Support\IssueMessageRecord;
use App\Persistence\Entities\Support\IssueRecord;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;
use Ramsey\Uuid\UuidInterface;

use function assert;
use function is_string;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class IssueMessage
{
    use Id;
    use Message;
    use User;
    use CreatedAt;
    use UpdatedAt;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private Issue $issue;

    public static function fromRecord(
        IssueMessageRecord $record,
        ?Issue $issue = null,
    ): self {
        if ($issue === null) {
            $issueRecord = $record->getIssue();

            assert($issueRecord instanceof IssueRecord);

            $issue = Issue::fromRecord($issueRecord);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $user = UserEntity::fromRecord(record: $record->getUser());

        return new self(
            id: $record->getId(),
            message: $record->getMessage(),
            user: $user,
            createdAt: $record->getCreatedAt(),
            updatedAt: $record->getUpdatedAt(),
            issue: $issue,
        );
    }

    public function __construct(
        string $message = '',
        ?UserEntity $user = null,
        null | string | DateTimeInterface $createdAt = null,
        null | string | DateTimeInterface $updatedAt = null,
        ?Issue $issue = null,
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

        $this->message = $message;

        $this->user = $user;

        // Created At
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
            new DateTimeZone('UTC'),
        );

        $this->createdAt = $createdAtClass;

        // Updated At
        if ($updatedAt instanceof DateTimeInterface) {
            $updatedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $updatedAt->format(DateTimeInterface::ATOM),
            );
        } elseif (is_string($updatedAt)) {
            $updatedAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $updatedAt,
            );
        } else {
            $updatedAtClass = new DateTimeImmutable();
        }

        assert($updatedAtClass instanceof DateTimeImmutable);

        $updatedAtClass = $updatedAtClass->setTimezone(
            new DateTimeZone('UTC'),
        );

        $this->updatedAt = $updatedAtClass;

        if ($issue !== null) {
            $this->issue = $issue;
        }

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function issue(): Issue
    {
        return $this->issue;
    }

    public function withIssue(Issue $issue): self
    {
        $clone = clone $this;

        $clone->issue = $issue;

        return $clone;
    }

    public function getCommentUserType(): string
    {
        return $this->userGuarantee()->isAdmin() ?
            Issue::USER_TYPE_ADMIN :
            Issue::USER_TYPE_USER;
    }
}
