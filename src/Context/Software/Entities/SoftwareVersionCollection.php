<?php

declare(strict_types=1);

namespace App\Context\Software\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(SoftwareVersion $element)
 * @method SoftwareVersion first()
 * @method SoftwareVersion last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method SoftwareVersionCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method SoftwareVersionCollection filter(callable $callback)
 * @method SoftwareVersionCollection where(string $propertyOrMethod, $value)
 * @method SoftwareVersionCollection map(callable $callback)
 * @method SoftwareVersion[] toArray()
 * @method SoftwareVersion|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, SoftwareVersion $item, bool $setLastIfNoMatch = false)
 */
class SoftwareVersionCollection extends AbstractCollection
{
    public function getType(): string
    {
        return SoftwareVersion::class;
    }
}
