<?php

declare(strict_types=1);

namespace App\Context\Schedule\Services;

use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Entities\ScheduleItem;
use App\Context\Schedule\Entities\ScheduleItemCollection;
use App\Persistence\Entities\Schedule\ScheduleTrackingRecord;
use App\Persistence\Entities\Schedule\ScheduleTrackingRecordCollection;
use Config\General;
use Config\ScheduleConfig;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_values;

class FetchSchedules
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    /** @phpstan-ignore-next-line  */
    public function fetch(): ScheduleItemCollection
    {
        try {
            return $this->innerFetch();
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught fetching schedule items',
                ['exception' => $exception],
            );

            return new ScheduleItemCollection();
        }
    }

    /** @phpstan-ignore-next-line  */
    private function innerFetch(): ScheduleItemCollection
    {
        $scheduleConfig = ScheduleConfig::getSchedule();

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $classNames = $scheduleConfig->map(
            static fn (ScheduleConfigItem $i) => $i->className(),
        )->toArray();

        $records = new ScheduleTrackingRecordCollection(
            (array) $this->entityManager
                ->getRepository(ScheduleTrackingRecord::class)
                ->createQueryBuilder('s')
                ->where('s.className IN(:classes)')
                ->setParameter(
                    'classes',
                    array_values($classNames)
                )
                ->getQuery()
                ->getResult()
        );

        /** @psalm-suppress MixedArgumentTypeCoercion */
        return new ScheduleItemCollection($scheduleConfig->map(
            static function (
                ScheduleConfigItem $configItem,
            ) use ($records): ScheduleItem {
                return ScheduleItem::fromConfigItemAndRecord(
                    $configItem,
                    $records->where(
                        'getClassName',
                        $configItem->className()
                    )->firstOrNull()
                );
            }
        )->toArray());
    }
}
