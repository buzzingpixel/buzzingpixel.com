<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Users;

use App\Context\Users\Entities\UserSession;
use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\LastTouchedAt;
use App\Persistence\PropertyTraits\UserId;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="user_sessions")
 */
class UserSessionRecord
{
    use Id;
    use UserId;
    use CreatedAt;
    use LastTouchedAt;

    public function hydrateFromEntity(UserSession $userSession): void
    {
        $this->setId(Uuid::fromString($userSession->id()));
        $this->setUserId(Uuid::fromString($userSession->userId()));
        $this->setCreatedAt($userSession->createdAt());
        $this->setLastTouchedAt($userSession->lastTouchedAt());
    }
}
