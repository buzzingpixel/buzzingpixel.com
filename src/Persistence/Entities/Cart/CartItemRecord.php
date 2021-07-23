<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Cart;

use App\Context\Cart\Entities\CartItem;
use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\Quantity;
use App\Persistence\PropertyTraits\Slug;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="cart_items")
 */
class CartItemRecord
{
    use Id;
    use Quantity;
    use Slug;

    public function hydrateFromEntity(CartItem $entity): self
    {
        $this->setId(Uuid::fromString($entity->id()));
        $this->setQuantity($entity->quantity());
        $this->setSlug($entity->slug());

        return $this;
    }

    /**
     * @Mapping\ManyToOne(
     *     targetEntity="CartRecord",
     *     inversedBy="cartItems",
     *     cascade={"persist"},
     * )
     * @Mapping\JoinColumn(
     *     name="cart_id",
     *     referencedColumnName="id",
     * )
     */
    private ?CartRecord $cart = null;

    public function getCart(): ?CartRecord
    {
        return $this->cart;
    }

    public function setCart(CartRecord $cart): void
    {
        $this->cart = $cart;
    }

    /**
     * @Mapping\OneToOne(
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
}
