<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Support;

use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\AdditionalEnvDetails;
use App\Persistence\PropertyTraits\CmsVersion;
use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsEnabled;
use App\Persistence\PropertyTraits\IsPublic;
use App\Persistence\PropertyTraits\IssueNumber;
use App\Persistence\PropertyTraits\LegacySolutionFile;
use App\Persistence\PropertyTraits\MySqlVersion;
use App\Persistence\PropertyTraits\PhpVersion;
use App\Persistence\PropertyTraits\PrivateInfo;
use App\Persistence\PropertyTraits\SoftwareVersion;
use App\Persistence\PropertyTraits\Solution;
use App\Persistence\PropertyTraits\SolutionFile;
use App\Persistence\PropertyTraits\Status;
use App\Persistence\PropertyTraits\UpdatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="issues")
 * @psalm-suppress PropertyNotSetInConstructor
 */
class IssueRecord
{
    use Id;
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
     * @Mapping\OrderBy({"createdAt" = "desc"})
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
     *     targetEntity="\App\Persistence\Entities\Support\IssueRecord",
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
}
