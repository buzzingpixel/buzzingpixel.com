<?php

declare(strict_types=1);

namespace App\Context\Orders\Entities;

use App\Collections\AbstractCollection;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method OrderItem first()
 * @method OrderItem last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method OrderItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method OrderItemCollection filter(callable $callback)
 * @method OrderItemCollection where(string $propertyOrMethod, $value)
 * @method OrderItemCollection map(callable $callback)
 * @method OrderItem[] toArray()
 * @method OrderItem|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, OrderItem $item, bool $setLastIfNoMatch = false)
 * @method OrderItemCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<OrderItem>
 */
class OrderItemCollection extends AbstractCollection
{
    /**
     * @param OrderItem[] $data
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
        return OrderItem::class;
    }
}
