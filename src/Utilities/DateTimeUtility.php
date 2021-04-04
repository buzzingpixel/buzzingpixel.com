<?php

declare(strict_types=1);

namespace App\Utilities;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

use function assert;

class DateTimeUtility
{
    public const FLATPICKR_DATETIME_FORMAT = 'Y-m-d';

    public const FLATPICKR_DATETIME_LOCAL_FORMAT = 'Y-m-d g:i A';

    public static function createDateTimeImmutableOrNull(
        null | string | DateTimeInterface $dateTime,
    ): ?DateTimeImmutable {
        if ($dateTime === null) {
            return null;
        }

        return self::createDateTimeImmutable($dateTime);
    }

    public static function createDateTimeImmutable(
        null | string | DateTimeInterface $dateTime,
    ): DateTimeImmutable {
        if ($dateTime === null) {
            return new DateTimeImmutable(
                timezone: new DateTimeZone('UTC'),
            );
        }

        if ($dateTime instanceof DateTimeInterface) {
            $class = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $dateTime->format(DateTimeInterface::ATOM),
            );
        } else {
            $class = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $dateTime,
            );
        }

        assert($class instanceof DateTimeImmutable);

        $class = $class->setTimezone(
            new DateTimeZone('UTC')
        );

        return $class;
    }
}
