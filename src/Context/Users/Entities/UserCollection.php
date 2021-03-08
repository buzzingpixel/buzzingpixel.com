<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use Ramsey\Collection\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(UserEntity $element)
 * @method UserEntity first()
 * @method UserEntity last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method UserCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method UserCollection filter(callable $callback)
 * @method UserCollection where(string $propertyOrMethod, $value)
 * @method UserCollection map(callable $callback)
 * @method UserCollection[] toArray()
 * @phpstan-ignore-next-line
 */
class UserCollection extends AbstractCollection
{
    public function getType(): string
    {
        return UserEntity::class;
    }
}
