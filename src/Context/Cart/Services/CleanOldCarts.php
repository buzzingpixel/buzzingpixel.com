<?php

declare(strict_types=1);

namespace App\Context\Cart\Services;

use App\Persistence\Entities\Cart\CartRecord;
use Config\General;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;

class CleanOldCarts
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
                'An exception was caught querying for old carts',
                ['exception' => $e],
            );

            return 0;
        }
    }

    public function innerClean(): int
    {
        $thirtyDaysAgo = new DateTimeImmutable(
            '30 days ago',
            new DateTimeZone('UTC')
        );

        $records = $this->entityManager
            ->getRepository(CartRecord::class)
            ->createQueryBuilder('c')
            ->where('c.lastTouchedAt < :lastTouchedAt')
            ->setParameter('lastTouchedAt', $thirtyDaysAgo->format(
                DateTimeInterface::ATOM,
            ))
            ->getQuery()
            ->toIterable();

        $batchSize = 20;

        $i = 1;

        $total = 0;

        foreach ($records as $record) {
            assert($record instanceof CartRecord);
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
