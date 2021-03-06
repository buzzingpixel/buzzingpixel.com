<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Users;

use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\LastTouchedAt;
use App\Persistence\PropertyTraits\UserId;
use Doctrine\ORM\Mapping;

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
}
