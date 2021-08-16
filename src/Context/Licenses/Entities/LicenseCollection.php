<?php

declare(strict_types=1);

namespace App\Context\Licenses\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(License $element)
 * @method License first()
 * @method License last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method LicenseCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method LicenseCollection filter(callable $callback)
 * @method LicenseCollection where(string $propertyOrMethod, $value)
 * @method LicenseCollection map(callable $callback)
 * @method License[] toArray()
 * @method License|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, License $item, bool $setLastIfNoMatch = false)
 * @method LicenseCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<License>
 */
class LicenseCollection extends AbstractCollection
{
    public function getType(): string
    {
        return License::class;
    }
}
