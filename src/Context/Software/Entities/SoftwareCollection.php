<?php

declare(strict_types=1);

namespace App\Context\Software\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(Software $element)
 * @method Software first()
 * @method Software last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method SoftwareCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method SoftwareCollection filter(callable $callback)
 * @method SoftwareCollection where(string $propertyOrMethod, $value)
 * @method SoftwareCollection map(callable $callback)
 * @method Software[] toArray()
 * @method Software|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, Software $item, bool $setLastIfNoMatch = false)
 */
class SoftwareCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Software::class;
    }
}
