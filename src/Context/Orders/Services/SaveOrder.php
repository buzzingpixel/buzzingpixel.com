<?php

declare(strict_types=1);

namespace App\Context\Orders\Services;

use App\Context\Orders\Entities\Order;
use App\Context\Orders\Events\SaveOrderFailed;
use App\Context\Orders\Factories\SaveOrderFactory;
use App\Payload\Payload;
use App\Persistence\Entities\Orders\OrderRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class SaveOrder
{
    public function __construct(
        private General $config,
        private LoggerInterface $logger,
        private EntityManager $entityManager,
        private SaveOrderFactory $saveOrderFactory,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function save(Order $order): Payload
    {
        try {
            return $this->innerSave($order);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                /** @noinspection PhpUnhandledExceptionInspection */
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught saving an order',
                ['exception' => $exception],
            );

            $this->eventDispatcher->dispatch(new SaveOrderFailed(
                order: $order,
                exception: $exception,
            ));

            return new Payload(
                status: Payload::STATUS_ERROR,
                result: ['message' => $exception->getMessage()],
            );
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    private function innerSave(Order $order): Payload
    {
        $this->logger->info(
            'Checking for existing order by ID: ' . $order->id()
        );

        $orderRecord = $this->entityManager->find(
            OrderRecord::class,
            $order->id(),
        );

        return $this->saveOrderFactory
            ->createSaveOrder(orderRecord: $orderRecord)
            ->save(order: $order, orderRecord: $orderRecord);
    }
}
