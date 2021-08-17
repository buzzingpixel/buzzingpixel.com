<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Software;

use App\Context\Software\Entities\Software;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsForSale;
use App\Persistence\PropertyTraits\IsSubscription;
use App\Persistence\PropertyTraits\Name;
use App\Persistence\PropertyTraits\Price;
use App\Persistence\PropertyTraits\RenewalPrice;
use App\Persistence\PropertyTraits\Slug;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="software")
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SoftwareRecord
{
    use Id;
    use Slug;
    use Name;
    use IsForSale;
    use Price;
    use RenewalPrice;
    use IsSubscription;

    public function hydrateFromEntity(Software $entity): self
    {
        $this->setId(Uuid::fromString($entity->id()));
        $this->setSlug($entity->slug());
        $this->setName($entity->name());
        $this->setIsForSale($entity->isForSale());
        $this->setPrice($entity->priceAsInt());
        $this->setRenewalPrice($entity->renewalPriceAsInt());
        $this->setIsSubscription($entity->isSubscription());

        return $this;
    }

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

    /**
     * One software has many software items. This is the inverse side.
     *
     * @var Collection<int, SoftwareVersionRecord>
     * @Mapping\OneToMany(
     *     targetEntity="SoftwareVersionRecord",
     *     mappedBy="software",
     *     cascade={"persist", "remove"},
     * )
     * @Mapping\OrderBy({"releasedOn" = "desc"})
     */
    private Collection $versions;

    /**
     * @return Collection<int, SoftwareVersionRecord>
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    /**
     * @param Collection<int, SoftwareVersionRecord> $versions
     */
    public function setVersions(Collection $versions): void
    {
        $this->versions = $versions;
    }
}
