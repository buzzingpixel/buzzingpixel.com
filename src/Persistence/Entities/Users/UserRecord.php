<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Users;

use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\EmailAddress;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsActive;
use App\Persistence\PropertyTraits\IsAdmin;
use App\Persistence\PropertyTraits\PasswordHash;
use App\Persistence\PropertyTraits\Timezone;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="users")
 */
class UserRecord
{
    use Id;
    use IsAdmin;
    use EmailAddress;
    use PasswordHash;
    use IsActive;
    use Timezone;
    use CreatedAt;
}
