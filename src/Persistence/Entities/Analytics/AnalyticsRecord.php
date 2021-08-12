<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Analytics;

use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\CookieId;
use App\Persistence\PropertyTraits\Date;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\LoggedInOnPageLoad;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="analytics")
 */
class AnalyticsRecord
{
    use Id;
    use CookieId;
    use LoggedInOnPageLoad;
    use Date;

    /**
     * @Mapping\ManyToOne(
     *     targetEntity="\App\Persistence\Entities\Users\UserRecord",
     * )
     * @Mapping\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
     *     nullable=true,
     * )
     */
    private ?UserRecord $user = null;

    public function getUser(): ?UserRecord
    {
        return $this->user;
    }

    public function setUser(?UserRecord $user = null): void
    {
        $this->user = $user;
    }
}
