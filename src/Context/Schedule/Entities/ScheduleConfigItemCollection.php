<?php

declare(strict_types=1);

namespace App\Context\Schedule\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(ScheduleConfigItem $element)
 * @method ScheduleConfigItem first()
 * @method ScheduleConfigItem last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method ScheduleConfigItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method ScheduleConfigItemCollection filter(callable $callback)
 * @method ScheduleConfigItemCollection where(string $propertyOrMethod, $value)
 * @method ScheduleConfigItemCollection map(callable $callback)
 * @method ScheduleConfigItem[] toArray()
 * @method ScheduleConfigItem|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, ScheduleConfigItem $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<ScheduleConfigItem>
 */
class ScheduleConfigItemCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ScheduleConfigItem::class;
    }
}
