<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(ValidityErrorMessage $element)
 * @method ValidityErrorMessage first()
 * @method ValidityErrorMessage last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method ValidityErrorMessageCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method ValidityErrorMessageCollection filter(callable $callback)
 * @method ValidityErrorMessageCollection where(string $propertyOrMethod, $value)
 * @method ValidityErrorMessageCollection map(callable $callback)
 * @method ValidityErrorMessage[] toArray()
 * @method ValidityErrorMessage|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, ValidityErrorMessage $item, bool $setLastIfNoMatch = false)
 * @method ValidityErrorMessageCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<ValidityErrorMessage>
 */
class ValidityErrorMessageCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ValidityErrorMessage::class;
    }
}
