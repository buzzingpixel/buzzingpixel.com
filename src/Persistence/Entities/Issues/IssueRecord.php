<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Issues;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueMessage;
use App\Context\Users\Entities\User;
use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\AdditionalEnvDetails;
use App\Persistence\PropertyTraits\CmsVersion;
use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsEnabled;
use App\Persistence\PropertyTraits\IsPublic;
use App\Persistence\PropertyTraits\IssueNumber;
use App\Persistence\PropertyTraits\LastCommentAt;
use App\Persistence\PropertyTraits\LastCommentUserType;
use App\Persistence\PropertyTraits\LegacySolutionFile;
use App\Persistence\PropertyTraits\MySqlVersion;
use App\Persistence\PropertyTraits\PhpVersion;
use App\Persistence\PropertyTraits\PrivateInfo;
use App\Persistence\PropertyTraits\ShortDescription;
use App\Persistence\PropertyTraits\SoftwareVersion;
use App\Persistence\PropertyTraits\Solution;
use App\Persistence\PropertyTraits\SolutionFile;
use App\Persistence\PropertyTraits\Status;
use App\Persistence\PropertyTraits\UpdatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

use function assert;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="issues")
 * @psalm-suppress PropertyNotSetInConstructor
 */
class IssueRecord
{
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
    use LegacySolutionFile;
    use IsEnabled;
    use CreatedAt;
    use UpdatedAt;
    use LastCommentAt;
    use LastCommentUserType;

    public function __construct()
    {
        $this->issueMessages = new ArrayCollection();
    }

    /**
     * One queue has many queue items. This is the inverse side.
     *
     * @var Collection<int, IssueMessageRecord>
     * @Mapping\OneToMany(
     *     targetEntity="IssueMessageRecord",
     *     mappedBy="issue",
     *     cascade={"persist", "remove"},
     * )
     * @Mapping\OrderBy({"createdAt" = "asc"})
     */
    private Collection $issueMessages;

    /**
     * @return Collection<int, IssueMessageRecord>
     */
    public function getIssueMessages(): Collection
    {
        return $this->issueMessages;
    }

    /**
     * @param Collection<int, IssueMessageRecord> $issueMessages
     */
    public function setIssueMessages(Collection $issueMessages): void
    {
        $this->issueMessages = $issueMessages;
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
     * @Mapping\ManyToOne(
     *     targetEntity="\App\Persistence\Entities\Software\SoftwareRecord",
     * )
     * @Mapping\JoinColumn(
     *     name="software_id",
     *     referencedColumnName="id",
     *     nullable=true,
     * )
     */
    private ?SoftwareRecord $software = null;

    public function getSoftware(): ?SoftwareRecord
    {
        return $this->software;
    }

    public function setSoftware(?SoftwareRecord $software): void
    {
        $this->software = $software;
    }

    /**
     * @Mapping\ManyToOne(
     *     targetEntity="IssueRecord",
     * )
     * @Mapping\JoinColumn(
     *     name="duplicate_of",
     *     referencedColumnName="id",
     *     nullable=true,
     * )
     */
    private ?IssueRecord $duplicateOf = null;

    public function getDuplicateOf(): ?IssueRecord
    {
        return $this->duplicateOf;
    }

    public function setDuplicateOf(?IssueRecord $duplicateOf): void
    {
        $this->duplicateOf = $duplicateOf;
    }

    public function hydrateFromEntity(
        Issue $entity,
        EntityManager $entityManager
    ): self {
        $this->setId(Uuid::fromString(uuid: $entity->id()));
        $this->setShortDescription(
            shortDescription: $entity->shortDescription()
        );
        $this->setIssueNumber(issueNumber: $entity->issueNumber());
        $this->setStatus(status: $entity->status());
        $this->setIsPublic(isPublic: $entity->isPublic());
        $this->setSoftwareVersion(softwareVersion: $entity->softwareVersion());
        $this->setCmsVersion(cmsVersion: $entity->cmsVersion());
        $this->setPhpVersion(phpVersion: $entity->phpVersion());
        $this->setMySqlVersion(mySqlVersion: $entity->mySqlVersion());
        $this->setAdditionalEnvDetails(
            additionalEnvDetails: $entity->additionalEnvDetails()
        );
        $this->setPrivateInfo(privateInfo: $entity->privateInfo());
        $this->setSolution(solution: $entity->solution());
        $this->setSolutionFile(solutionFile: $entity->solutionFile());
        $this->setLegacySolutionFile(
            legacySolutionFile: $entity->legacySolutionFile()
        );
        $this->setIsEnabled(isEnabled: $entity->isEnabled());
        $this->setCreatedAt(createdAt: $entity->createdAt());
        $this->setUpdatedAt(updatedAt: $entity->updatedAt());
        $this->setLastCommentAt(lastCommentAt: $entity->lastCommentAt());
        $this->setLastCommentUserType(
            lastCommentUserType: $entity->lastCommentUserType()
        );

        $this->setIssueMessages(issueMessages: new ArrayCollection(
            $entity->issueMessages()->mapToArray(
                function (IssueMessage $iM) use (
                    $entityManager,
                ): IssueMessageRecord {
                    $iRecord = $this->getIssueMessages()->filter(
                        static fn (
                            IssueMessageRecord $r
                        ) => $r->getId()->toString() === $iM->id(),
                    )->first();

                    $isInstance = $iRecord instanceof IssueMessageRecord;

                    if (! $isInstance) {
                        $iRecord = new IssueMessageRecord();
                    }

                    assert($iRecord instanceof IssueMessageRecord);

                    return $iRecord->hydrateFromEntity(
                        entity: $iM,
                        entityManager: $entityManager,
                        issueRecord: $this,
                    );
                },
            ),
        ));

        $software = $entity->software();

        if ($software === null) {
            $this->setSoftware(null);
        } else {
            /** @noinspection PhpUnhandledExceptionInspection */
            $softwareRecord = $entityManager->find(
                SoftwareRecord::class,
                $software->id(),
            );

            $this->setSoftware($softwareRecord);
        }

        $user = $entity->user();

        assert($user instanceof User);

        /** @noinspection PhpUnhandledExceptionInspection */
        $userRecord = $entityManager->find(
            UserRecord::class,
            $user->id(),
        );

        assert($userRecord instanceof UserRecord);

        $this->setUser($userRecord);

        return $this;
    }
}
