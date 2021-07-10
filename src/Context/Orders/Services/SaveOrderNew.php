<?php

declare(strict_types=1);

namespace App\Context\Orders\Services;

use App\Context\Orders\Contracts\SaveOrder;
use App\Context\Orders\Entities\Order;
use App\Context\Orders\Events\SaveOrderAfterSave;
use App\Context\Orders\Events\SaveOrderBeforeSave;
use App\Context\Orders\Factories\OrderValidityFactory;
use App\Payload\Payload;
use App\Persistence\Entities\Orders\OrderRecord;
use Doctrine\ORM\EntityManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

use function implode;

class SaveOrderNew implements SaveOrder
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManager $entityManager,
        private EventDispatcherInterface $eventDispatcher,
        private OrderValidityFactory $orderValidityFactory,
    ) {
    }

    public function save(
        Order $order,
        ?OrderRecord $orderRecord = null
    ): Payload {
        $this->logger->info(
            'Creating new Order record'
        );

        $validity = $this->orderValidityFactory->createOrderValidity(
            order: $order,
        );

        if (! $validity->isValid()) {
            $this->logger->error(
                'The License entity is invalid',
                [
                    'orderEntity' => $order,
                    'orderValidity' => $validity,
                ],
            );

            return new Payload(
                status: $validity->payloadStatusText(),
                result: [
                    'message' => implode(
                        ' ',
                        $validity->validationErrors(),
                    ),
                ],
            );
        }

        $beforeSave = new SaveOrderBeforeSave(order: $order);

        $this->eventDispatcher->dispatch($beforeSave);

        $order = $beforeSave->order;

        $record = new OrderRecord();

        $record->hydrateFromEntity(
            entity: $order,
            entityManager: $this->entityManager
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->persist($record);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->flush();

        $payload = new Payload(
            status: Payload::STATUS_CREATED,
            result: ['orderEntity' => $order],
        );

        $afterSave = new SaveOrderAfterSave(
            order: $order,
            payload: $payload,
        );

        $this->eventDispatcher->dispatch($afterSave);

        $this->logger->info('The Order was saved');

        return $afterSave->payload;
    }
}
