<?php

declare(strict_types=1);

namespace App\Context\Issues\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Issue $element)
 * @method Issue first()
 * @method Issue last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method IssueCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method IssueCollection filter(callable $callback)
 * @method IssueCollection where(string $propertyOrMethod, $value)
 * @method IssueCollection map(callable $callback)
 * @method Issue[] toArray()
 * @method Issue|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Issue $item, bool $setLastIfNoMatch = false)
 * @method IssueCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<Issue>
 */
class IssueCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Issue::class;
    }
}
