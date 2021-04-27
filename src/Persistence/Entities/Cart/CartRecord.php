<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Cart;

use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\CreatedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\LastTouchedAt;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="cart")
 */
class CartRecord
{
    use Id;
    use LastTouchedAt;
    use CreatedAt;

    /**
     * @Mapping\OneToOne(
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
    public function getOrderItems(): Collection
    {
        return $this->cartItems;
    }

    /**
     * @param Collection<int, CartItemRecord> $cartItems
     */
    public function setOrderItems(Collection $cartItems): void
    {
        $this->cartItems = $cartItems;
    }
}
