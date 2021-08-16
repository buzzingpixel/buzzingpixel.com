<?php

declare(strict_types=1);

namespace App\Context\Schedule\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(ScheduleItem $element)
 * @method ScheduleItem first()
 * @method ScheduleItem last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method ScheduleItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method ScheduleItemCollection filter(callable $callback)
 * @method ScheduleItemCollection where(string $propertyOrMethod, $value)
 * @method ScheduleItemCollection map(callable $callback)
 * @method ScheduleItem[] toArray()
 * @method ScheduleItem|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, ScheduleItem $item, bool $setLastIfNoMatch = false)
 * @method ScheduleItemCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<ScheduleItem>
 */
class ScheduleItemCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ScheduleItem::class;
    }
}
