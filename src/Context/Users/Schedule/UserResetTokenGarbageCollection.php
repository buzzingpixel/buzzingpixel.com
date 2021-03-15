<?php

declare(strict_types=1);

namespace App\Context\Users\Schedule;

use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;
use App\Persistence\Entities\Users\UserPasswordResetTokenRecord;
use App\Utilities\SystemClock;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Safe\Exceptions\DatetimeException;

use function strtotime;

class UserResetTokenGarbageCollection
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::FIVE_MINUTES,
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
        $twoHoursAgo = $this->systemClock->getCurrentTime()
            ->setTimestamp(strtotime('2 hours ago'))
            ->setTimezone(new DateTimeZone('UTC'));

        $this->entityManager->createQueryBuilder()
            ->delete(UserPasswordResetTokenRecord::class, 't')
            ->where('t.createdAt < :createdAt')
            ->setParameter('createdAt', $twoHoursAgo->format(
                DateTimeInterface::ATOM,
            ))
            ->getQuery()
            ->execute();
    }
}
