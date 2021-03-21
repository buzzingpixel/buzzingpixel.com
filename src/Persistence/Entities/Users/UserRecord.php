<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Users;

use App\Context\Users\Entities\User;
use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\EmailAddress;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsActive;
use App\Persistence\PropertyTraits\IsAdmin;
use App\Persistence\PropertyTraits\PasswordHash;
use App\Persistence\PropertyTraits\Timezone;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="users")
 * @psalm-suppress PropertyNotSetInConstructor
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

    /**
     * One user has one support profile
     *
     * @Mapping\OneToOne(
     *     targetEntity="UserSupportProfileRecord",
     *     cascade={"persist", "remove"},
     * )
     * @Mapping\JoinColumn(
     *     name="support_profile_id",
     *     referencedColumnName="id",
     * )
     */
    private UserSupportProfileRecord $supportProfile;

    public function getSupportProfile(): UserSupportProfileRecord
    {
        return $this->supportProfile;
    }

    public function setSupportProfile(
        UserSupportProfileRecord $supportProfile,
    ): void {
        $this->supportProfile = $supportProfile;
    }

    public function hydrateFromEntity(User $user): void
    {
        $this->setId(Uuid::fromString($user->id()));
        $this->setIsAdmin($user->isAdmin());
        $this->setEmailAddress($user->emailAddress());
        $this->setPasswordHash($user->passwordHash());
        $this->setIsActive($user->isActive());
        $this->setTimezone($user->timezone()->getName());
        $this->setCreatedAt($user->createdAt());
    }

    public function __construct()
    {
        $this->supportProfile = new UserSupportProfileRecord();
    }
}
