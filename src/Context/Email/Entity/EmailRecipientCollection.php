<?php

declare(strict_types=1);

namespace App\Context\Email\Entity;

use Ramsey\Collection\AbstractCollection;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method bool add(EmailRecipient $element)
 * @method EmailRecipient first()
 * @method EmailRecipient last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method EmailRecipientCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method EmailRecipientCollection filter(callable $callback)
 * @method EmailRecipientCollection where(string $propertyOrMethod, $value)
 * @method EmailRecipientCollection map(callable $callback)
 * @method EmailRecipient[] toArray()
 * @phpstan-ignore-next-line
 */
class EmailRecipientCollection extends AbstractCollection
{
    public function getType(): string
    {
        return EmailRecipient::class;
    }
}
