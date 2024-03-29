<?php

declare(strict_types=1);

namespace App\Context\Queue\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Queue $element)
 * @method Queue first()
 * @method Queue last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method QueueCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method QueueCollection filter(callable $callback)
 * @method QueueCollection where(string $propertyOrMethod, $value)
 * @method QueueCollection map(callable $callback)
 * @method Queue[] toArray()
 * @method Queue|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Queue $item, bool $setLastIfNoMatch = false)
 * @method QueueCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<Queue>
 */
class QueueCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Queue::class;
    }
}
