<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Users;

use App\Context\Users\Entities\UserPasswordResetTokenEntity;
use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\UserId;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="user_password_reset_tokens")
 */
class UserPasswordResetTokenRecord
{
    use Id;
    use UserId;
    use CreatedAt;

    public function hydrateFromEntity(UserPasswordResetTokenEntity $entity): void
    {
        $this->setId(Uuid::fromString($entity->id()));
        $this->setUserId(Uuid::fromString($entity->userId()));
        $this->setCreatedAt($entity->createdAt());
    }
}
