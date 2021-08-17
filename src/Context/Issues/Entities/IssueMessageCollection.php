<?php

declare(strict_types=1);

namespace App\Context\Issues\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(IssueMessage $element)
 * @method IssueMessage first()
 * @method IssueMessage last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method IssueMessageCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method IssueMessageCollection filter(callable $callback)
 * @method IssueMessageCollection where(string $propertyOrMethod, $value)
 * @method IssueMessageCollection map(callable $callback)
 * @method IssueMessage[] toArray()
 * @method IssueMessage|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, IssueMessage $item, bool $setLastIfNoMatch = false)
 * @method IssueMessageCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<IssueMessage>
 */
class IssueMessageCollection extends AbstractCollection
{
    public function getType(): string
    {
        return IssueMessage::class;
    }
}
