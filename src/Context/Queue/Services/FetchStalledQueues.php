<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\QueueCollection;
use App\Context\Queue\Entities\QueueEntity;
use App\Persistence\Entities\Queue\QueueRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;

class FetchStalledQueues
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function fetch(): QueueCollection
    {
        try {
            return $this->innerFetch();
        } catch (Throwable $e) {
            if ($this->config->devMode()) {
                throw $e;
            }

            $this->logger->emergency(
                'An exception was caught querying for stalled queues',
                ['exception' => $e],
            );

            return new QueueCollection();
        }
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function innerFetch(): QueueCollection
    {
        /** @psalm-suppress MixedArgument */
        return new QueueCollection(array_map(
            static fn (QueueRecord $r) => QueueEntity::fromRecord(
                $r
            ),
            $this->entityManager
                ->getRepository(QueueRecord::class)
                ->createQueryBuilder('q')
                ->where('q.finishedDueToError = true')
                ->orderBy('q.addedAt', 'asc')
                ->getQuery()
                ->getResult()
        ));
    }
}
