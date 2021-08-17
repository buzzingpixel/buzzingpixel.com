<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Support;

use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\Message;
use App\Persistence\PropertyTraits\UpdatedAt;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Mapping;
use LogicException;

/**
 * @Mapping\Entity
 * @Mapping\HasLifecycleCallbacks
 * @Mapping\Table(name="issue_messages")
 */
class IssueMessageRecord
{
    use Id;
    use Message;
    use CreatedAt;
    use UpdatedAt;

    /**
     * Many queue items have one queue. This is the owning side.
     *
     * @Mapping\ManyToOne(
     *     targetEntity="IssueRecord",
     *     inversedBy="issueMessages",
     *     cascade={"persist"},
     * )
     * @Mapping\JoinColumn(
     *     name="issue_id",
     *     referencedColumnName="id",
     * )
     */
    private IssueRecord $issue;

    /**
     * Returns null if software ID has been set and doesn't match
     */
    public function getIssue(): ?IssueRecord
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (! isset($this->issue)) {
            return null;
        }

        if (
            $this->newIssueId !== null &&
            $this->issue->getId()->toString() !== $this->newIssueId
        ) {
            return null;
        }

        return $this->issue;
    }

    public function setIssue(IssueRecord $issue): void
    {
        $this->issue = $issue;
    }

    private ?string $newIssueId = null;

    public function getNewIssueId(): ?string
    {
        return $this->newIssueId;
    }

    public function setNewIssueId(string $newIssueId): void
    {
        $this->newIssueId = $newIssueId;
    }

    /**
     * @Mapping\ManyToOne(
     *     targetEntity="\App\Persistence\Entities\Users\UserRecord",
     * )
     * @Mapping\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
     * )
     */
    private UserRecord $user;

    public function getUser(): UserRecord
    {
        return $this->user;
    }

    public function setUser(UserRecord $user): void
    {
        $this->user = $user;
    }

    /**
     * @Mapping\PreFlush
     */
    public function preFlushSetSoftwareFromNewId(PreFlushEventArgs $args): void
    {
        if ($this->getNewIssueId() === null) {
            return;
        }

        /**
         * @psalm-suppress RedundantCondition
         * @psalm-suppress RedundantPropertyInitializationCheck
         */
        if (
            isset($this->issue) &&
            $this->getNewIssueId() === $this->issue->getId()->toString()
        ) {
            return;
        }

        $issue = $args->getEntityManager()
            ->getRepository(IssueRecord::class)
            ->find($this->newIssueId);

        if ($issue === null) {
            throw new LogicException('No issue found');
        }

        $this->setIssue($issue);
    }
}
