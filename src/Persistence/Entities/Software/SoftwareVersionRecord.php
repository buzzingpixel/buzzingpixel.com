<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Software;

use App\Context\Software\Entities\SoftwareVersion;
use App\Persistence\PropertyTraits\DownloadFile;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\MajorVersion;
use App\Persistence\PropertyTraits\ReleasedOn;
use App\Persistence\PropertyTraits\UpgradePrice;
use App\Persistence\PropertyTraits\Version;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Mapping;
use LogicException;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\HasLifecycleCallbacks
 * @Mapping\Table(name="software_versions")
 */
class SoftwareVersionRecord
{
    use Id;
    use MajorVersion;
    use Version;
    use DownloadFile;
    use UpgradePrice;
    use ReleasedOn;

    /**
     * Many queue items have one queue. This is the owning side.
     *
     * @Mapping\ManyToOne(
     *     targetEntity="SoftwareRecord",
     *     inversedBy="versions",
     *     cascade={"persist"},
     * )
     * @Mapping\JoinColumn(
     *     name="software_id",
     *     referencedColumnName="id",
     * )
     */
    private SoftwareRecord $software;

    /**
     * Returns null if software ID has been set and doesn't match
     */
    public function getSoftware(): ?SoftwareRecord
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (! isset($this->software)) {
            return null;
        }

        if (
            $this->newSoftwareId !== null &&
            $this->software->getId()->toString() !== $this->newSoftwareId
        ) {
            return null;
        }

        return $this->software;
    }

    public function setSoftware(SoftwareRecord $software): void
    {
        $this->software = $software;
    }

    private ?string $newSoftwareId = null;

    public function getNewSoftwareId(): ?string
    {
        return $this->newSoftwareId;
    }

    public function setNewSoftwareId(string $newSoftwareId): void
    {
        $this->newSoftwareId = $newSoftwareId;
    }

    public function hydrateFromEntity(SoftwareVersion $entity): self
    {
        $this->setId(Uuid::fromString($entity->id()));
        $this->setMajorVersion($entity->majorVersion());
        $this->setVersion($entity->version());
        $this->setDownloadFile($entity->downloadFile());
        $this->setUpgradePrice($entity->upgradePriceAsInt());
        $this->setReleasedOn($entity->releasedOn());

        return $this;
    }

    /**
     * @Mapping\PreFlush
     */
    public function preFlushSetSoftwareFromNewId(PreFlushEventArgs $args): void
    {
        if ($this->getNewSoftwareId() === null) {
            return;
        }

        /**
         * @psalm-suppress RedundantCondition
         * @psalm-suppress RedundantPropertyInitializationCheck
         */
        if (
            isset($this->software) &&
            $this->getNewSoftwareId() === $this->software->getId()->toString()
        ) {
            return;
        }

        $software = $args->getEntityManager()
            ->getRepository(SoftwareRecord::class)
            ->find($this->newSoftwareId);

        if ($software === null) {
            throw new LogicException('No software found');
        }

        $this->setSoftware($software);
    }
}
