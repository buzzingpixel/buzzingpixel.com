<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueCollection;
use App\Persistence\Entities\Queue\QueueRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;
use function assert;
use function is_array;

class FetchLastXFinishedQueues
{
    public function __construct(
        private General $config,
        private LoggerInterface $logger,
        private EntityManager $entityManager,
    ) {
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @phpstan-ignore-next-line
     */
    public function fetch(int $maxResults = 10): QueueCollection
    {
        try {
            return $this->innerFetch();
        } catch (Throwable $e) {
            if ($this->config->devMode()) {
                throw $e;
            }

            $this->logger->emergency(
                'An exception was caught querying for next ten queue items',
                ['exception' => $e],
            );

            return new QueueCollection();
        }
    }

    /** @phpstan-ignore-next-line */
    public function innerFetch(int $maxResults = 10): QueueCollection
    {
        $records = $this->entityManager
            ->getRepository(QueueRecord::class)
            ->createQueryBuilder('q')
            ->where('q.isFinished = true')
            ->where('q.finishedDueToError = false')
            ->setMaxResults($maxResults)
            ->orderBy('q.addedAt', 'asc')
            ->getQuery()
            ->getResult();

        assert(is_array($records));

        return new QueueCollection(array_map(
            static fn (QueueRecord $q) => Queue::fromRecord(
                record: $q
            ),
            $records,
        ));
    }
}
