<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\QueueItem;
use Psr\Container\ContainerInterface;

class RunQueueItem
{
    public function __construct(private ContainerInterface $di)
    {
    }

    public function run(QueueItem $queueItem): void
    {
        /** @psalm-suppress MixedAssignment */
        $class = $this->di->get($queueItem->className());

        /**
         * @psalm-suppress MixedMethodCall
         * @phpstan-ignore-next-line
         */
        $class->{$queueItem->methodName()}($queueItem->context());
    }
}
