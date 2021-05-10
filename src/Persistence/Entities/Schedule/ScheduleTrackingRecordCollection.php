<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Schedule;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(ScheduleTrackingRecord $element)
 * @method ScheduleTrackingRecord first()
 * @method ScheduleTrackingRecord last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method ScheduleTrackingRecordCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method ScheduleTrackingRecordCollection filter(callable $callback)
 * @method ScheduleTrackingRecordCollection where(string $propertyOrMethod, $value)
 * @method ScheduleTrackingRecordCollection map(callable $callback)
 * @method ScheduleTrackingRecord[] toArray()
 * @method ScheduleTrackingRecord|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, ScheduleTrackingRecord $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<ScheduleTrackingRecord>
 */
class ScheduleTrackingRecordCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ScheduleTrackingRecord::class;
    }
}
