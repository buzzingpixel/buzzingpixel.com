<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Licenses;

use App\Context\Licenses\Entities\License;
use App\Context\Software\Entities\Software;
use App\Context\Users\Entities\User;
use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\AdminNotes;
use App\Persistence\PropertyTraits\AuthorizedDomains;
use App\Persistence\PropertyTraits\ExpiresAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsDisabled;
use App\Persistence\PropertyTraits\LicenseKey;
use App\Persistence\PropertyTraits\MajorVersion;
use App\Persistence\PropertyTraits\StripeCanceledAt;
use App\Persistence\PropertyTraits\StripeStatus;
use App\Persistence\PropertyTraits\StripeSubscriptionAmount;
use App\Persistence\PropertyTraits\StripeSubscriptionId;
use App\Persistence\PropertyTraits\StripeSubscriptionItemId;
use App\Persistence\PropertyTraits\UserNotes;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

use function assert;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="licenses")
 */
class LicenseRecord
{
    use Id;
    use IsDisabled;
    use MajorVersion;
    use LicenseKey;
    use UserNotes;
    use AdminNotes;
    use AuthorizedDomains;
    use ExpiresAt;
    use StripeStatus;
    use StripeSubscriptionId;
    use StripeSubscriptionItemId;
    use StripeCanceledAt;
    use StripeSubscriptionAmount;

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
     * @Mapping\ManyToOne(
     *     targetEntity="\App\Persistence\Entities\Software\SoftwareRecord"
     * )
     * @Mapping\JoinColumn(
     *     name="software_id",
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

    public function hydrateFromEntity(
        License $entity,
        EntityManager $entityManager,
    ): self {
        $this->setId(Uuid::fromString(uuid: $entity->id()));
        $this->setIsDisabled(isDisabled: $entity->isDisabled());
        $this->setMajorVersion(majorVersion: $entity->majorVersion());
        $this->setLicenseKey(licenseKey: $entity->licenseKey());
        $this->setUserNotes(userNotes: $entity->userNotes());
        $this->setAdminNotes(adminNotes: $entity->adminNotes());
        $this->setAuthorizedDomains(authorizedDomains: $entity->authorizedDomains());
        $this->setExpiresAt(expiresAt: $entity->expiresAt());
        $this->setStripeStatus(stripeStatus: $entity->stripeStatus());
        $this->setStripeSubscriptionId(stripeSubscriptionId: $entity->stripeSubscriptionId());
        $this->setStripeSubscriptionItemId(stripeSubscriptionItemId: $entity->stripeSubscriptionItemId());
        $this->setStripeCanceledAt(stripeCanceledAt: $entity->stripeCanceledAt());
        $this->setStripeSubscriptionAmount(stripeSubscriptionAmount: $entity->stripeSubscriptionAmountAsInt());

        $user = $entity->user();

        assert($user instanceof User);

        /** @noinspection PhpUnhandledExceptionInspection */
        $userRecord = $entityManager->find(
            UserRecord::class,
            $user->id(),
        );

        assert($userRecord instanceof UserRecord);

        $this->setUser($userRecord);

        $software = $entity->software();

        assert($software instanceof Software);

        /** @noinspection PhpUnhandledExceptionInspection */
        $softwareRecord = $entityManager->find(
            SoftwareRecord::class,
            $software->id(),
        );

        assert($softwareRecord instanceof SoftwareRecord);

        $this->setSoftware($softwareRecord);

        return $this;
    }
}
