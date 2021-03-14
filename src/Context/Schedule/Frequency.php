<?php

declare(strict_types=1);

namespace App\Context\Schedule;

use function is_numeric;
use function mb_strtolower;

class Frequency
{
    public const ALWAYS                = 'Always';
    public const FIVE_MINUTES          = 'FiveMinutes';
    public const TEN_MINUTES           = 'TenMinutes';
    public const THIRTY_MINUTES        = 'ThirtyMinutes';
    public const HOUR                  = 'Hour';
    public const DAY                   = 'Day';
    public const WEEK                  = 'Week';
    public const MONTH                 = 'Month';
    public const DAY_AT_MIDNIGHT       = 'DayAtMidnight';
    public const SATURDAY_AT_MIDNIGHT  = 'SaturdayAtMidnight';
    public const SUNDAY_AT_MIDNIGHT    = 'SundayAtMidnight';
    public const MONDAY_AT_MIDNIGHT    = 'MondayAtMidnight';
    public const TUESDAY_AT_MIDNIGHT   = 'TuesdayAtMidnight';
    public const WEDNESDAY_AT_MIDNIGHT = 'WednesdayAtMidnight';
    public const THURSDAY_AT_MIDNIGHT  = 'ThursdayAtMidNight';
    public const FRIDAY_AT_MIDNIGHT    = 'FridayAtMidnight';

    public const MAP = [
        'always' => 0,
        'fiveminutes' => 5,
        'tenminutes' => 10,
        'thirtyminutes' => 30,
        'hour' => 60,
        'day' => 1440,
        'week' => 10080,
        'month' => 43800,
        'dayatmidnight' => 'dayatmidnight',
        'saturdayatmidnight' => 'saturdayatmidnight',
        'sundayatmidnight' => 'sundayatmidnight',
        'mondayatmidnight' => 'mondayatmidnight',
        'tuesdayatmidnight' => 'tuesdayatmidnight',
        'wednesdayatmidnight' => 'wednesdayatmidnight',
        'thursdayatmidnight' => 'thursdayatmidnight',
        'fridayatmidnight' => 'fridayatmidnight',
    ];

    /**
     * Translates run every into actionable values.
     * - If the value of runEvery is numeric, it is assumed to be minutes and
     *   will be converted to seconds
     * - Else if the runEvery value is not set on the RUN_EVERY_MAP, a 0 will be
     *   returned (same value as always)
     * - Else if the runEvery mapped value is numeric, it is minutes and will be
     *   converted to seconds and returned
     * - Else the mapped value will be returned
     */
    public static function getTranslatedValue(
        float | int | string $val
    ): float | int | string {
        if (is_numeric($val)) {
            return ((int) $val) * 60;
        }

        $val = mb_strtolower($val);

        if (! isset(self::MAP[$val])) {
            return 0;
        }

        $mappedVal = self::MAP[$val];

        if (is_numeric($mappedVal)) {
            /** @phpstan-ignore-next-line  */
            $mappedVal = (int) $mappedVal;

            return $mappedVal * 60;
        }

        return $mappedVal;
    }
}
