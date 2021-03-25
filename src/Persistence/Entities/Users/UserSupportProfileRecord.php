<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Users;

use App\Context\Users\Entities\UserSupportProfile;
use App\Persistence\PropertyTraits\DisplayName;
use App\Persistence\PropertyTraits\Id;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="user_support_profiles")
 */
class UserSupportProfileRecord
{
    use Id;
    use DisplayName;

    public function hydrateFromEntity(UserSupportProfile $supportProfile): void
    {
        $this->setDisplayName($supportProfile->displayName());
    }
}
