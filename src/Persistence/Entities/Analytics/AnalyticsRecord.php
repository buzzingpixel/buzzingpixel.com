<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Analytics;

use App\Context\Analytics\Entities\AnalyticsEntity;
use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\CookieId;
use App\Persistence\PropertyTraits\Date;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\LoggedInOnPageLoad;
use App\Persistence\PropertyTraits\Uri;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="analytics")
 */
class AnalyticsRecord
{
    use Id;
    use CookieId;
    use LoggedInOnPageLoad;
    use Uri;
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

    public function hydrateFromEntity(
        AnalyticsEntity $entity,
        EntityManager $entityManager,
    ): self {
        /** @noinspection PhpUnhandledExceptionInspection */
        $userRecord = $entity->user() !== null ? $entityManager->find(
            UserRecord::class,
            $entity->userGuarantee()->id(),
        ) : null;

        $this->setId(id: Uuid::fromString($entity->id()));
        $this->setCookieId(cookieId: Uuid::fromString(
            $entity->cookie()->value()
        ));
        $this->setLoggedInOnPageLoad(
            loggedInOnPageLoad: $entity->loggedInOnPageLoad()
        );
        $this->setDate(date: $entity->date());
        $this->setUri(uri: $entity->uri());
        $this->setUser(user: $userRecord);

        return $this;
    }
}
