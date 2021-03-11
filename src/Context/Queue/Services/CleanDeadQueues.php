<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Persistence\Entities\Queue\QueueRecord;
use Config\General;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;
use Psr\Log\LoggerInterface;
use Safe\DateTimeImmutable;
use Safe\Exceptions\DatetimeException;
use Throwable;

use function assert;

class CleanDeadQueues
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function clean(): int
    {
        try {
            return $this->innerClean();
        } catch (Throwable $e) {
            if ($this->config->devMode()) {
                throw $e;
            }

            $this->logger->emergency(
                'An exception was caught querying for dead queues',
                ['exception' => $e],
            );

            return 0;
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MappingException
     * @throws DatetimeException
     */
    private function innerClean(): int
    {
        $currentDate = new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC')
        );

        $records = $this->entityManager
            ->getRepository(QueueRecord::class)
            ->createQueryBuilder('q')
            ->where('q.isRunning = true')
            ->andWhere('q.assumeDeadAfter < :assumeDeadAfter')
            ->setParameter('assumeDeadAfter', $currentDate->format(
                DateTimeInterface::ATOM,
            ))
            ->getQuery()
            ->toIterable();

        $batchSize = 20;

        $i = 1;

        $total = 0;

        foreach ($records as $record) {
            assert($record instanceof QueueRecord);
            $total++;

            $this->entityManager->remove($record);

            if ($i % $batchSize === 0) {
                $this->entityManager->flush(); // Executes all deletions.
                $this->entityManager->clear(); // Detaches all objects from Doctrine
            }

            ++$i;
        }

        $this->entityManager->flush();

        return $total;
    }
}
