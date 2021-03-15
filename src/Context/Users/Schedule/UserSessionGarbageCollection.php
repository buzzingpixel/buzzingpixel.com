<?php

declare(strict_types=1);

namespace App\Context\Users\Schedule;

use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;
use App\Persistence\Entities\Users\UserSessionRecord;
use App\Utilities\SystemClock;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Safe\Exceptions\DatetimeException;

use function strtotime;

class UserSessionGarbageCollection
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::DAY_AT_MIDNIGHT,
        );
    }

    public function __construct(
        private EntityManager $entityManager,
        private SystemClock $systemClock,
    ) {
    }

    /**
     * @throws DatetimeException
     */
    public function __invoke(): void
    {
        $thirtyDaysAgo = $this->systemClock->getCurrentTime()
            ->setTimestamp(strtotime('30 days ago'))
            ->setTimezone(new DateTimeZone('UTC'));

        $this->entityManager->createQueryBuilder()
            ->delete(UserSessionRecord::class, 's')
            ->where('s.lastTouchedAt < :lastTouchedAt')
            ->setParameter('lastTouchedAt', $thirtyDaysAgo->format(
                DateTimeInterface::ATOM,
            ))
            ->getQuery()
            ->execute();
    }
}
