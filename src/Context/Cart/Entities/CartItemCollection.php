<?php

declare(strict_types=1);

namespace App\Context\Cart\Entities;

use App\Collections\AbstractCollection;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method CartItem first()
 * @method CartItem last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method CartItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method CartItemCollection filter(callable $callback)
 * @method CartItemCollection where(string $propertyOrMethod, $value)
 * @method CartItemCollection map(callable $callback)
 * @method CartItem[] toArray()
 * @method CartItem|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, CartItem $item, bool $setLastIfNoMatch = false)
 */
class CartItemCollection extends AbstractCollection
{
    /**
     * @param CartItem[] $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct();

        foreach ($data as $datum) {
            $this->add($datum);
        }
    }

    public function getType(): string
    {
        return CartItem::class;
    }
}
