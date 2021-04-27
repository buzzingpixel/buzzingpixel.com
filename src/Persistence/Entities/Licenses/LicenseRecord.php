<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Licenses;

use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\AdminNotes;
use App\Persistence\PropertyTraits\AuthorizedDomains;
use App\Persistence\PropertyTraits\ExpiresAt;
use App\Persistence\PropertyTraits\HasBeenUpgraded;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsDisabled;
use App\Persistence\PropertyTraits\IsUpgrade;
use App\Persistence\PropertyTraits\LicenseKey;
use App\Persistence\PropertyTraits\MajorVersion;
use App\Persistence\PropertyTraits\UserNotes;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="licenses")
 */
class LicenseRecord
{
    use Id;
    use IsDisabled;
    use MajorVersion;
    use IsUpgrade;
    use HasBeenUpgraded;
    use LicenseKey;
    use UserNotes;
    use AdminNotes;
    use AuthorizedDomains;
    use ExpiresAt;

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
     * @Mapping\OneToOne(
     *     targetEntity="\App\Persistence\Entities\Software\SoftwareRecord"
     * )
     * @Mapping\JoinColumn(
     *     name="softare_id",
     *     referencedColumnName="id",
     * )
     */
    private SoftwareRecord $software;

    public function getSoftware(): SoftwareRecord
    {
        return $this->software;
    }

    public function setSoftware(SoftwareRecord $software): void
    {
        $this->software = $software;
    }
}
