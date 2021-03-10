<?php

declare(strict_types=1);

namespace App\Context\Queue\Entities;

use Ramsey\Collection\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(QueueEntity $element)
 * @method QueueEntity first()
 * @method QueueEntity last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method QueueCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method QueueCollection filter(callable $callback)
 * @method QueueCollection where(string $propertyOrMethod, $value)
 * @method QueueCollection map(callable $callback)
 * @method QueueCollection[] toArray()
 * @phpstan-ignore-next-line
 */
class QueueCollection extends AbstractCollection
{
    public function getType(): string
    {
        return QueueEntity::class;
    }
}
