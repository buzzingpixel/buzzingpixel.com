<?php

declare(strict_types=1);

namespace App\Context\Issues\Entities;

use App\Context\Software\Entities\Software as SoftwareEntity;
use App\Context\Users\Entities\User as UserEntity;
use App\EntityPropertyTraits\AdditionalEnvDetails;
use App\EntityPropertyTraits\CmsVersion;
use App\EntityPropertyTraits\CreatedAt;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\IsEnabled;
use App\EntityPropertyTraits\IsPublic;
use App\EntityPropertyTraits\IssueNumber;
use App\EntityPropertyTraits\LastCommentAt;
use App\EntityPropertyTraits\LastCommentUserType;
use App\EntityPropertyTraits\LegacySolutionFile;
use App\EntityPropertyTraits\MySqlVersion;
use App\EntityPropertyTraits\NewSolutionFileLocation;
use App\EntityPropertyTraits\PhpVersion;
use App\EntityPropertyTraits\PrivateInfo;
use App\EntityPropertyTraits\ShortDescription;
use App\EntityPropertyTraits\Software;
use App\EntityPropertyTraits\SoftwareVersion;
use App\EntityPropertyTraits\Solution;
use App\EntityPropertyTraits\SolutionFile;
use App\EntityPropertyTraits\Status;
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

use function array_map;
use function array_merge;
use function array_values;
use function assert;
use function is_array;
use function is_string;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class Issue
{
    public const STATUS_NEW                     = 'new';
    public const STATUS_CLARIFICATION_REQUESTED = 'clarificationRequested';
    public const STATUS_ACCEPTED                = 'accepted';
    public const STATUS_DUPLICATE               = 'duplicate';
    public const STATUS_FIX_IN_UPCOMING_RELEASE = 'fixIsInUpcomingRelease';
    public const STATUS_COMPLETE                = 'complete';
    public const STATUS_SEE_COMMENTS            = 'seeComments';
    public const ALL_STATUSES                   = [
        self::STATUS_NEW => self::STATUS_NEW,
        self::STATUS_CLARIFICATION_REQUESTED => self::STATUS_CLARIFICATION_REQUESTED,
        self::STATUS_ACCEPTED => self::STATUS_ACCEPTED,
        self::STATUS_DUPLICATE => self::STATUS_DUPLICATE,
        self::STATUS_FIX_IN_UPCOMING_RELEASE => self::STATUS_FIX_IN_UPCOMING_RELEASE,
        self::STATUS_COMPLETE => self::STATUS_COMPLETE,
        self::STATUS_SEE_COMMENTS => self::STATUS_SEE_COMMENTS,
    ];
    public const HUMAN_READABLE_STATUS_MAP      = [
        self::STATUS_NEW => 'New',
        self::STATUS_CLARIFICATION_REQUESTED => 'Clarification Requested',
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_DUPLICATE => 'Duplicate',
        self::STATUS_FIX_IN_UPCOMING_RELEASE => 'Fix in upcoming release',
        self::STATUS_COMPLETE => 'Complete',
        self::STATUS_SEE_COMMENTS => 'See Comments',
    ];
    public const STATUS_COLOR_MAP               = [
        self::STATUS_NEW => 'gray',
        self::STATUS_CLARIFICATION_REQUESTED => 'yellow',
        self::STATUS_ACCEPTED => 'gray',
        self::STATUS_DUPLICATE => 'gray',
        self::STATUS_FIX_IN_UPCOMING_RELEASE => 'green',
        self::STATUS_COMPLETE => 'green',
        self::STATUS_SEE_COMMENTS => 'gray',
    ];
    public const USER_TYPE_ADMIN                = 'admin';
    public const USER_TYPE_USER                 = 'user';
    use Id;
    use ShortDescription;
    use IssueNumber;
    use Status;
    use IsPublic;
    use SoftwareVersion;
    use CmsVersion;
    use PhpVersion;
    use MySqlVersion;
    use AdditionalEnvDetails;
    use PrivateInfo;
    use Solution;
    use SolutionFile;
    use NewSolutionFileLocation;
    use LegacySolutionFile;
    use IsEnabled;
    use User;
    use Software;
    use CreatedAt;
    use UpdatedAt;
    use LastCommentAt;
    use LastCommentUserType;

    public function humanReadableStatus(): string
    {
        return self::HUMAN_READABLE_STATUS_MAP[$this->status];
    }

    public function statusColor(): string
    {
        return self::STATUS_COLOR_MAP[$this->status];
    }

    /** @phpstan-ignore-next-line */
    private IssueMessageCollection $issueMessages;

    public static function fromRecord(IssueRecord $record): self
    {
        $software = null;

        $softwareRecord = $record->getSoftware();

        if ($softwareRecord !== null) {
            $software = SoftwareEntity::fromRecord(record: $softwareRecord);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $user = UserEntity::fromRecord(record: $record->getUser());

        return (new self(
            id: $record->getId(),
            shortDescription: $record->getShortDescription(),
            issueNumber: $record->getIssueNumber(),
            status: $record->getStatus(),
            isPublic: $record->getIsPublic(),
            softwareVersion: $record->getSoftwareVersion(),
            cmsVersion: $record->getCmsVersion(),
            phpVersion: $record->getPhpVersion(),
            mySqlVersion: $record->getMySqlVersion(),
            additionalEnvDetails: $record->getAdditionalEnvDetails(),
            privateInfo: $record->getPrivateInfo(),
            solution: $record->getSolution(),
            solutionFile: $record->getSolutionFile(),
            legacySolutionFile: $record->getLegacySolutionFile(),
            isEnabled: $record->getIsEnabled(),
            user: $user,
            software: $software,
            createdAt: $record->getCreatedAt(),
            updatedAt: $record->getUpdatedAt(),
            lastCommentAt: $record->getLastCommentAt(),
            lastCommentUserType: $record->getLastCommentUserType(),
        ))->withIssueMessagesFromRecord($record);
    }

    /** @phpstan-ignore-next-line */
    public function __construct(
        string $shortDescription = '',
        int $issueNumber = 0,
        string $status = self::STATUS_NEW,
        bool $isPublic = true,
        string $softwareVersion = '',
        string $cmsVersion = '',
        string $phpVersion = '',
        string $mySqlVersion = '',
        string $additionalEnvDetails = '',
        string $privateInfo = '',
        string $solution = '',
        string $solutionFile = '',
        string $newSolutionFileLocation = '',
        string $legacySolutionFile = '',
        bool $isEnabled = true,
        null | string | DateTimeInterface $createdAt = null,
        null | string | DateTimeInterface $updatedAt = null,
        null | string | DateTimeInterface $lastCommentAt = null,
        string $lastCommentUserType = self::USER_TYPE_USER,
        ?UserEntity $user = null,
        ?SoftwareEntity $software = null,
        null | array | IssueMessageCollection $issueMessages = null,
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

        $this->shortDescription = $shortDescription;

        $this->issueNumber = $issueNumber;

        $this->status = $status;

        $this->isPublic = $isPublic;

        $this->softwareVersion = $softwareVersion;

        $this->cmsVersion = $cmsVersion;

        $this->phpVersion = $phpVersion;

        $this->mySqlVersion = $mySqlVersion;

        $this->additionalEnvDetails = $additionalEnvDetails;

        $this->privateInfo = $privateInfo;

        $this->solution = $solution;

        $this->solutionFile = $solutionFile;

        $this->newSolutionFileLocation = $newSolutionFileLocation;

        $this->legacySolutionFile = $legacySolutionFile;

        $this->isEnabled = $isEnabled;

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
            /** @noinspection PhpUnhandledExceptionInspection */
            $createdAtClass = new DateTimeImmutable();
        }

        assert($createdAtClass instanceof DateTimeImmutable);

        $createdAtClass = $createdAtClass->setTimezone(
            new DateTimeZone('UTC'),
        );

        $this->createdAt = $createdAtClass;
        // End created At

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
            /** @noinspection PhpUnhandledExceptionInspection */
            $updatedAtClass = new DateTimeImmutable(
                'now',
                new DateTimeZone('UTC'),
            );
        }

        assert($updatedAtClass instanceof DateTimeImmutable);

        $updatedAtClass = $updatedAtClass->setTimezone(
            new DateTimeZone('UTC'),
        );

        $this->updatedAt = $updatedAtClass;
        // End updated at

        // Last Comment At
        if ($lastCommentAt instanceof DateTimeInterface) {
            $lastCommentAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastCommentAt->format(
                    DateTimeInterface::ATOM
                ),
            );
        } elseif (is_string($lastCommentAt)) {
            $lastCommentAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $lastCommentAt,
            );
        } else {
            /** @noinspection PhpUnhandledExceptionInspection */
            $lastCommentAtClass = new DateTimeImmutable(
                'now',
                new DateTimeZone('UTC'),
            );
        }

        assert($lastCommentAtClass instanceof DateTimeImmutable);

        $lastCommentAtClass = $lastCommentAtClass->setTimezone(
            new DateTimeZone('UTC'),
        );

        $this->lastCommentAt = $lastCommentAtClass;
        // End last comment at

        $this->lastCommentUserType = $lastCommentUserType;

        $this->user = $user;

        $this->software = $software;

        if ($issueMessages === null) {
            $this->issueMessages = new IssueMessageCollection();
        } elseif (is_array($issueMessages)) {
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $this->issueMessages = new IssueMessageCollection(
                $issueMessages,
            );
        } else {
            $this->issueMessages = $issueMessages;
        }

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    /** @phpstan-ignore-next-line */
    public function issueMessages(): IssueMessageCollection
    {
        return $this->issueMessages;
    }

    /** @phpstan-ignore-next-line */
    public function withIssueMessages(
        array | IssueMessageCollection $issueMessages
    ): self {
        $clone = clone $this;

        if (! is_array($issueMessages)) {
            $issueMessages = $issueMessages->toArray();
        }

        $clone->issueMessages = new IssueMessageCollection(array_map(
            static fn (IssueMessage $i) => $i->withIssue(
                $clone,
            ),
            array_values($issueMessages),
        ));

        return $clone;
    }

    public function withAddedIssueMessage(IssueMessage $newIssueMessage): self
    {
        $clone = clone $this;

        $clone->issueMessages = new IssueMessageCollection(array_map(
            static fn (IssueMessage $i) => $i->withIssue(
                $clone,
            ),
            array_merge(
                $this->issueMessages->toArray(),
                [$newIssueMessage],
            ),
        ));

        return $clone;
    }

    public function withIssueMessagesFromRecord(IssueRecord $record): self
    {
        $clone = clone $this;

        $clone->issueMessages = new IssueMessageCollection(array_map(
            static fn (
                IssueMessageRecord $record,
            ) => IssueMessage::fromRecord(
                record: $record,
                issue: $clone,
            ),
            $record->getIssueMessages()->toArray(),
        ));

        return $clone;
    }

    public function withIssueMessageFromIssueMessasgeRecord(
        IssueMessageRecord $record,
    ): self {
        $clone = clone $this;

        $issueMessages = new IssueMessageCollection(
            $this->issueMessages->toArray(),
        );

        $issueMessages->add(IssueMessage::fromRecord(
            record: $record,
            issue: $clone,
        ));

        return $clone;
    }
}
