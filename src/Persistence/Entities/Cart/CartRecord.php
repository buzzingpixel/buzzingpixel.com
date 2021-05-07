<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Cart;

use App\Context\Cart\Entities\Cart;
use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\LastTouchedAt;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="cart")
 */
class CartRecord
{
    use Id;
    use LastTouchedAt;
    use CreatedAt;

    public function hydrateFromEntity(
        Cart $entity,
        EntityManager $entityManager,
    ): self {
        $this->setId(Uuid::fromString($entity->id()));
        $this->setLastTouchedAt($entity->lastTouchedAt());
        $this->setCreatedAt($entity->createdAt());

        $user = $entity->user();

        if ($user !== null) {
            $this->setUser($entityManager->find(
                UserRecord::class,
                $user->id(),
            ));
        } else {
            $this->setUser();
        }

        return $this;
    }

    /**
     * @Mapping\OneToOne(
     *     targetEntity="\App\Persistence\Entities\Users\UserRecord",
     * )
     * @Mapping\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
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

    /**
     * @var Collection<int, CartItemRecord>
     * @Mapping\OneToMany(
     *     targetEntity="CartItemRecord",
     *     mappedBy="cart",
     *     cascade={"persist", "remove"},
     * )
     * @Mapping\OrderBy({"slug" = "asc"})
     */
    private Collection $cartItems;

    /**
     * @return Collection<int, CartItemRecord>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    /**
     * @param Collection<int, CartItemRecord> $cartItems
     */
    public function setCartItems(Collection $cartItems): void
    {
        $this->cartItems = $cartItems;
    }
}
