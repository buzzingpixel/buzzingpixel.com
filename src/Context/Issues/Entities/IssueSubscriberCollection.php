<?php

declare(strict_types=1);

namespace App\Context\Issues\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(IssueSubscriber $element)
 * @method IssueSubscriber first()
 * @method IssueSubscriber last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method IssueSubscriberCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method IssueSubscriberCollection filter(callable $callback)
 * @method IssueSubscriberCollection where(string $propertyOrMethod, $value)
 * @method IssueSubscriberCollection map(callable $callback)
 * @method IssueSubscriber[] toArray()
 * @method IssueSubscriber|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, IssueSubscriber $item, bool $setLastIfNoMatch = false)
 * @method IssueSubscriberCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<IssueSubscriber>
 */
class IssueSubscriberCollection extends AbstractCollection
{
    public function getType(): string
    {
        return IssueSubscriber::class;
    }
}
