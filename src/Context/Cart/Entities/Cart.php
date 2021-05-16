<?php

declare(strict_types=1);

namespace App\Context\Cart\Entities;

use App\Context\Users\Entities\User as UserEntity;
use App\EntityPropertyTraits\CreatedAt;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\LastTouchedAt;
use App\EntityPropertyTraits\User;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Cart\CartItemRecord;
use App\Persistence\Entities\Cart\CartRecord;
use App\Utilities\DateTimeUtility;
use App\Utilities\MoneyFormatter;
use DateTimeInterface;
use LogicException;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

use function array_filter;
use function array_map;
use function array_merge;
use function assert;
use function is_array;
use function ltrim;
use function round;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class Cart
{
    use Id;
    use LastTouchedAt;
    use CreatedAt;
    use User;

    /** @phpstan-ignore-next-line  */
    private CartItemCollection $cartItems;

    public static function fromRecord(CartRecord $record): self
    {
        /** @psalm-suppress PossiblyNullArgument */
        return (new self(
            id: $record->getId(),
            lastTouchedAt: $record->getLastTouchedAt(),
            createdAt: $record->getCreatedAt(),
            user: $record->getUser() !== null ? UserEntity::fromRecord(
                $record->getUser()
            ) :
            null,
        ))->withItemsFromRecord($record);
    }

    /** @phpstan-ignore-next-line  */
    public function __construct(
        ?UserEntity $user = null,
        null | array | CartItemCollection $cartItems = [],
        null | string | DateTimeInterface $lastTouchedAt = null,
        null | string | DateTimeInterface $createdAt = null,
        null | string | UuidInterface $id = null,
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

        $this->user = $user;

        if ($cartItems instanceof CartItemCollection) {
            $cartItems = $cartItems->toArray();
        } elseif (! is_array($cartItems)) {
            $cartItems = [];
        }

        $this->cartItems = new CartItemCollection(array_map(
            fn (CartItem $i) => $i->withCart(
                $this,
            ),
            array_merge($cartItems)
        ));

        $this->lastTouchedAt = DateTimeUtility::createDateTimeImmutable(
            $lastTouchedAt,
        );

        $this->createdAt = DateTimeUtility::createDateTimeImmutable(
            $createdAt,
        );
    }

    private bool $isInitialized = false;

    /** @phpstan-ignore-next-line */
    public function cartItems(): CartItemCollection
    {
        return $this->cartItems;
    }

    /** @phpstan-ignore-next-line  */
    public function withCartItems(array | CartItemCollection $cartItems): self
    {
        $clone = clone $this;

        if (! is_array($cartItems)) {
            $cartItems = $cartItems->toArray();
        }

        $clone->cartItems = new CartItemCollection(array_map(
            static fn (CartItem $i) => $i->withCart(
                $clone
            ),
            array_merge($cartItems)
        ));

        return $clone;
    }

    public function withAddedCartItem(CartItem $newCartItem): self
    {
        $clone = clone $this;

        $newSlug = $newCartItem->slug();

        $this->cartItems()->walk(
            static function (CartItem $i) use (
                &$newCartItem,
                &$clone,
            ): void {
                /**
                 * Psalm needs the assert for... stupid reasons... who knows
                 *
                 * @phpstan-ignore-next-line
                 */
                assert($newCartItem instanceof CartItem);

                /**
                 * Psalm needs the assert for... stupid reasons... who knows
                 *
                 * @phpstan-ignore-next-line
                 */
                assert($clone instanceof Cart);

                if ($i->slug() !== $newCartItem->slug()) {
                    return;
                }

                $clone->removedCartItemIds[] = $i->id();

                $newCartItem = $newCartItem->withQuantity(
                    $i->quantity() + $newCartItem->quantity(),
                );
            }
        );

        /**
         * Psalm needs the assert for... stupid reasons... who knows
         *
         * @phpstan-ignore-next-line
         */
        assert($clone instanceof Cart);

        $clone->cartItems = new CartItemCollection(array_map(
            static fn (CartItem $i) => $i->withCart(
                $clone
            ),
            array_merge(
                array_filter(
                    $this->cartItems->toArray(),
                    static fn (CartItem $i) => $i->slug() !== $newSlug,
                ),
                [$newCartItem]
            ),
        ));

        return $clone;
    }

    /** @var string[] */
    private array $removedCartItemIds = [];

    /**
     * @return string[]
     */
    public function removedCartItemIds(): array
    {
        return $this->removedCartItemIds;
    }

    public function withRemovedCartItemBySlug(string $slug): self
    {
        $clone = clone $this;

        array_map(
            static function (CartItem $i) use ($slug, &$clone): void {
                /**
                 * Psalm needs the assert for... stupid reasons... who knows
                 *
                 * @phpstan-ignore-next-line
                 */
                assert($clone instanceof Cart);

                if ($i->slug() !== $slug) {
                    return;
                }

                $clone->removedCartItemIds[] = $i->id();
            },
            $this->cartItems->toArray(),
        );

        /**
         * Psalm needs the assert for... stupid reasons... who knows
         *
         * @phpstan-ignore-next-line
         */
        assert($clone instanceof Cart);

        $clone->cartItems = new CartItemCollection(array_map(
            static fn (CartItem $i) => $i->withCart(
                $clone
            ),
            array_filter(
                $this->cartItems->toArray(),
                static fn (CartItem $i) => $i->slug() !== $slug,
            ),
        ));

        return $clone;
    }

    public function withItemsFromRecord(CartRecord $record): self
    {
        $clone = clone $this;

        $clone->cartItems = new CartItemCollection(array_map(
            static fn (
                CartItemRecord $r
            ) => CartItem::fromRecord(
                $r,
                $clone,
            ),
            $record->getCartItems()->toArray(),
        ));

        return $clone;
    }

    public function totalItems(): int
    {
        return $this->cartItems()->count();
    }

    public function totalQuantity(): int
    {
        $quantity = 0;

        foreach ($this->cartItems()->toArray() as $item) {
            $quantity += $item->quantity();
        }

        return $quantity;
    }

    public function subTotal(): Money
    {
        $money = new Money(0, new Currency('USD'));

        foreach ($this->cartItems()->toArray() as $item) {
            for ($i = 0; $i < $item->quantity(); $i++) {
                $money = $money->add($item->software()->price());
            }
        }

        return $money;
    }

    public function subTotalAsInt(): int
    {
        return (int) $this->subTotal()->getAmount();
    }

    public function subTotalFormatted(): string
    {
        return MoneyFormatter::format($this->subTotal());
    }

    public function subTotalFormattedNoSymbol(): string
    {
        return ltrim($this->subTotalFormatted(), '$');
    }

    /**
     * Calculate 7% sales tax if in the state of TN
     */
    public function tax(string $stateAbbr = ''): Money
    {
        $usd = new Currency('USD');

        $user = $this->user();

        if ($user === null && $stateAbbr !== 'TN') {
            return new Money(0, $usd);
        }

        if ($stateAbbr === '' && $user !== null) {
            $stateAbbr = $user->billingProfile()->billingStateProvince();
        }

        if ($stateAbbr !== 'TN') {
            return new Money(0, new Currency('USD'));
        }

        return new Money(
            (int) round(
                ((int) $this->subTotal()->getAmount()) * 0.07
            ),
            $usd
        );
    }

    public function taxAsInt(): int
    {
        return (int) $this->tax()->getAmount();
    }

    public function taxFormatted(): string
    {
        return MoneyFormatter::format($this->tax());
    }

    public function taxFormattedNoSymbol(): string
    {
        return ltrim($this->taxFormatted(), '$');
    }

    public function total(): Money
    {
        return $this->subTotal()->add($this->tax());
    }

    public function totalAsInt(): int
    {
        return (int) $this->total()->getAmount();
    }

    public function totalFormatted(): string
    {
        return MoneyFormatter::format($this->total());
    }

    public function totalFormattedNoSymbol(): string
    {
        return ltrim($this->totalFormatted(), '$');
    }
}
