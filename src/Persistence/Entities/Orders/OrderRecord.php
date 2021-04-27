<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Orders;

use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\PropertyTraits\BillingAddress;
use App\Persistence\PropertyTraits\BillingAddressContinued;
use App\Persistence\PropertyTraits\BillingCity;
use App\Persistence\PropertyTraits\BillingCompany;
use App\Persistence\PropertyTraits\BillingCountryRegion;
use App\Persistence\PropertyTraits\BillingName;
use App\Persistence\PropertyTraits\BillingPhone;
use App\Persistence\PropertyTraits\BillingPostalCode;
use App\Persistence\PropertyTraits\BillingStateProvince;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\OldOrderNumber;
use App\Persistence\PropertyTraits\OrderDate;
use App\Persistence\PropertyTraits\StripeAmount;
use App\Persistence\PropertyTraits\StripeBalanceTransaction;
use App\Persistence\PropertyTraits\StripeCaptured;
use App\Persistence\PropertyTraits\StripeCreated;
use App\Persistence\PropertyTraits\StripeCurrency;
use App\Persistence\PropertyTraits\StripeId;
use App\Persistence\PropertyTraits\StripePaid;
use App\Persistence\PropertyTraits\SubTotal;
use App\Persistence\PropertyTraits\Tax;
use App\Persistence\PropertyTraits\Total;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="orders")
 * @psalm-suppress PropertyNotSetInConstructor
 */
class OrderRecord
{
    use Id;
    use OldOrderNumber;
    use StripeId;
    use StripeAmount;
    use StripeBalanceTransaction;
    use StripeCaptured;
    use StripeCreated;
    use StripeCurrency;
    use StripePaid;
    use SubTotal;
    use Tax;
    use Total;
    use BillingName;
    use BillingCompany;
    use BillingPhone;
    use BillingCountryRegion;
    use BillingAddress;
    use BillingAddressContinued;
    use BillingCity;
    use BillingStateProvince;
    use BillingPostalCode;
    use OrderDate;

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
     * @var Collection<int, OrderItemRecord>
     * @Mapping\OneToMany(
     *     targetEntity="OrderItemRecord",
     *     mappedBy="order",
     *     cascade={"persist", "remove"},
     * )
     * @Mapping\OrderBy({"title" = "asc"})
     */
    private Collection $orderItems;

    /**
     * @return Collection<int, OrderItemRecord>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    /**
     * @param Collection<int, OrderItemRecord> $orderItems
     */
    public function setOrderItems(Collection $orderItems): void
    {
        $this->orderItems = $orderItems;
    }
}
