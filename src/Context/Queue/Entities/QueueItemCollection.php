<?php

declare(strict_types=1);

namespace App\Context\Queue\Entities;

use Ramsey\Collection\AbstractCollection;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method QueueItemEntity first()
 * @method QueueItemEntity last()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method QueueItemCollection sort(string $propertyOrMethod, string $order = self::SORT_ASC)
 * @method QueueItemCollection filter(callable $callback)
 * @method QueueItemCollection where(string $propertyOrMethod, $value)
 * @method QueueItemCollection map(callable $callback)
 * @method QueueItemEntity[] toArray()
 * @phpstan-ignore-next-line
 */
class QueueItemCollection extends AbstractCollection
{
    /**
     * @param QueueItemEntity[] $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct();

        foreach ($data as $datum) {
            $this->add($datum);
        }
    }

    public function getType(): string
    {
        return QueueItemEntity::class;
    }

    /**
     * @param QueueItemEntity $element
     *
     * @noinspection PhpMissingParamTypeInspection
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     * @phpstan-ignore-next-line
     */
    public function add($element): bool
    {
        return parent::add($element->withRunOrder($this->count() + 1));
    }
}
