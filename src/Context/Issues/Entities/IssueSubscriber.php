<?php

declare(strict_types=1);

namespace App\Context\Issues\Entities;

use App\Context\Users\Entities\User as UserEntity;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\IsActive;
use App\EntityPropertyTraits\User;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Issues\IssueRecord;
use App\Persistence\Entities\Issues\IssueSubscriberRecord;
use LogicException;
use Ramsey\Uuid\UuidInterface;

use function assert;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class IssueSubscriber
{
    use Id;
    use User;
    use IsActive;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private Issue $issue;

    public static function fromRecord(
        IssueSubscriberRecord $record,
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
            user: $user,
            isActive: $record->getIsActive(),
            issue: $issue,
        );
    }

    public function __construct(
        ?UserEntity $user = null,
        bool $isActive = true,
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

        $this->user = $user;

        $this->isActive = $isActive;

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
}
