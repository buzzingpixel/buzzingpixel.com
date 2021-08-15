<?php

declare(strict_types=1);

namespace App\Context\Content\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(ContentItem $element)
 * @method ContentItem first()
 * @method ContentItem last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method ContentItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method ContentItemCollection filter(callable $callback)
 * @method ContentItemCollection where(string $propertyOrMethod, $value)
 * @method ContentItemCollection map(callable $callback)
 * @method ContentItem[] toArray()
 * @method ContentItem|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, ContentItem $item, bool $setLastIfNoMatch = false)
 * @template-extends AbstractCollection<ContentItem>
 */
class ContentItemCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ContentItem::class;
    }
}
