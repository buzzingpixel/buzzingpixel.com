<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\Collections\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(User $element)
 * @method User first()
 * @method User last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method UserCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method UserCollection filter(callable $callback)
 * @method UserCollection where(string $propertyOrMethod, $value)
 * @method UserCollection map(callable $callback)
 * @method User[] toArray()
 * @method User|null firstOrNull()
 * @method void replaceWhereMatch(string $propertyOrMethod, User $item, bool $setLastIfNoMatch = false)
 * @method UserCollection slice(int $offset = 0, ?int $length = null)
 * @template-extends AbstractCollection<User>
 */
class UserCollection extends AbstractCollection
{
    public function getType(): string
    {
        return User::class;
    }
}
