<?php

declare(strict_types=1);

namespace App\Context\Orders\Entities;

use App\Context\Users\Entities\User as UserEntity;
use App\EntityPropertyTraits\BillingAddress;
use App\EntityPropertyTraits\BillingAddressContinued;
use App\EntityPropertyTraits\BillingCity;
use App\EntityPropertyTraits\BillingCompany;
use App\EntityPropertyTraits\BillingCountryRegion;
use App\EntityPropertyTraits\BillingName;
use App\EntityPropertyTraits\BillingPhone;
use App\EntityPropertyTraits\BillingPostalCode;
use App\EntityPropertyTraits\BillingStateProvince;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\OldOrderNumber;
use App\EntityPropertyTraits\OrderDate;
use App\EntityPropertyTraits\StripeAmount;
use App\EntityPropertyTraits\StripeBalanceTransaction;
use App\EntityPropertyTraits\StripeCaptured;
use App\EntityPropertyTraits\StripeCreated;
use App\EntityPropertyTraits\StripeCurrency;
use App\EntityPropertyTraits\StripeId;
use App\EntityPropertyTraits\StripePaid;
use App\EntityPropertyTraits\SubTotal;
use App\EntityPropertyTraits\Tax;
use App\EntityPropertyTraits\Total;
use App\EntityPropertyTraits\User;
use App\EntityValueObjects\Id as IdValue;
use App\Utilities\DateTimeUtility;
use DateTimeInterface;
use LogicException;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

use function array_map;
use function array_merge;
use function is_array;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class Order
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
    use User;

    /** @phpstan-ignore-next-line */
    private OrderItemCollection $orderItems;

    /** @phpstan-ignore-next-line */
    public function __construct(
        string $olderOrderNumber = '',
        string $stripeId = '',
        string $stripeAmount = '',
        string $stripeBalanceTransaction = '',
        bool $stripeCaptured = false,
        string $stripeCreated = '',
        string $stripeCurrency = '',
        bool $stripePaid = false,
        int | Money $subTotal = 0,
        int | Money $tax = 0,
        int | Money $total = 0,
        string $billingName = '',
        string $billingCompany = '',
        string $billingPhone = '',
        string $billingCountryRegion = '',
        string $billingAddress = '',
        string $billingAddressContinued = '',
        string $billingCity = '',
        string $billingStateProvince = '',
        string $billingPostalCode = '',
        null | string | DateTimeInterface $orderDate = null,
        null | string | UuidInterface $id = null,
        null | array | OrderItemCollection $orderItems = [],
        ?UserEntity $user = null,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        if ($id === null) {
            $this->id = IdValue::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = IdValue::fromString($id->toString());
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->oldOrderNumber = $olderOrderNumber;

        $this->stripeId = $stripeId;

        $this->stripeAmount = $stripeAmount;

        $this->stripeBalanceTransaction = $stripeBalanceTransaction;

        $this->stripeCaptured = $stripeCaptured;

        $this->stripeCreated = $stripeCreated;

        $this->stripeCurrency = $stripeCurrency;

        $this->stripePaid = $stripePaid;

        if ($subTotal instanceof Money) {
            $this->subTotal = $subTotal;
        } else {
            $this->subTotal = new Money(
                $subTotal,
                new Currency('USD')
            );
        }

        if ($tax instanceof Money) {
            $this->tax = $tax;
        } else {
            $this->tax = new Money(
                $tax,
                new Currency('USD')
            );
        }

        if ($total instanceof Money) {
            $this->total = $total;
        } else {
            $this->total = new Money(
                $total,
                new Currency('USD')
            );
        }

        $this->billingName = $billingName;

        $this->billingCompany = $billingCompany;

        $this->billingPhone = $billingPhone;

        $this->billingCountryRegion = $billingCountryRegion;

        $this->billingAddress = $billingAddress;

        $this->billingAddressContinued = $billingAddressContinued;

        $this->billingCity = $billingCity;

        $this->billingStateProvince = $billingStateProvince;

        $this->billingPostalCode = $billingPostalCode;

        $this->orderDate = DateTimeUtility::createDateTimeImmutable(
            $orderDate,
        );

        if ($orderItems instanceof OrderItemCollection) {
            $orderItems = $orderItems->toArray();
        } elseif (! is_array($orderItems)) {
            $orderItems = [];
        }

        $this->orderItems = new OrderItemCollection(array_map(
            fn (OrderItem $i) => $i->withOrder($this),
            array_merge($orderItems),
        ));

        $this->user = $user;
    }

    private bool $isInitialized = false;

    /** @phpstan-ignore-next-line */
    public function orderItems(): OrderItemCollection
    {
        return $this->orderItems;
    }

    /** @phpstan-ignore-next-line */
    public function withOrderItems(array | OrderItemCollection $orderItems): self
    {
        $clone = clone $this;

        if (! is_array($orderItems)) {
            $orderItems = $orderItems->toArray();
        }

        $clone->orderItems = new OrderItemCollection(array_map(
            static fn (OrderItem $i) => $i->withOrder($clone),
            array_merge($orderItems),
        ));

        return $clone;
    }
}
