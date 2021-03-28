<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Software;

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

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

    /**
     * One queue has many queue items. This is the inverse side.
     *
     * @var Collection<int, SoftwareVersionRecord>
     * @Mapping\OneToMany(
     *     targetEntity="SoftwareVersionRecord",
     *     mappedBy="software",
     *     cascade={"persist", "remove"},
     * )
     * @Mapping\OrderBy({"released_on" = "desc"})
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
