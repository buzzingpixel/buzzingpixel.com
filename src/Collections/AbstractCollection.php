<?php

declare(strict_types=1);

namespace App\Collections;

use Ramsey\Collection\AbstractCollection as RamseyAbstractCollection;
use Ramsey\Collection\Exception\ValueExtractionException;

use function array_map;
use function array_walk;
use function get_class;
use function is_object;
use function method_exists;
use function property_exists;
use function sprintf;

// phpcs:disable SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming.SuperfluousPrefix
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint

/**
 * @template T
 * @template-extends RamseyAbstractCollection<T>
 */
abstract class AbstractCollection extends RamseyAbstractCollection
{
    /**
     * Extracts the value of the given property or method from the object.
     *
     * @param mixed  $object           The object to extract the value from.
     * @param string $propertyOrMethod The property or method for which the
     *     value should be extracted.
     *
     * @return mixed the value extracted from the specified property or method.
     *
     * @throws ValueExtractionException if the method or property is not defined.
     */
    protected function extractValue($object, string $propertyOrMethod): mixed
    {
        if (! is_object($object)) {
            throw new ValueExtractionException(
                'Unable to extract a value from a non-object'
            );
        }

        if (method_exists($object, $propertyOrMethod)) {
            /** @phpstan-ignore-next-line */
            return $object->{$propertyOrMethod}();
        }

        if (property_exists($object, $propertyOrMethod)) {
            /** @phpstan-ignore-next-line */
            return $object->$propertyOrMethod;
        }

        throw new ValueExtractionException(
            sprintf(
                'Method or property "%s" not defined in %s',
                $propertyOrMethod,
                get_class($object),
            ),
        );
    }

    /**
     * @return T|null
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function firstOrNull()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->first();
    }

    /**
     * @param T $item
     */
    public function replaceWhereMatch(
        string $propertyOrMethod,
        mixed $item,
        bool $setLastIfNoMatch = false,
    ): void {
        /** @psalm-suppress MixedAssignment */
        $value = $this->extractValue(
            $item,
            $propertyOrMethod,
        );

        $hasMatch = false;

        foreach ($this->data as $key => $loopItem) {
            /** @psalm-suppress MixedAssignment */
            $loopValue = $this->extractValue(
                $loopItem,
                $propertyOrMethod,
            );

            if ($loopValue !== $value) {
                continue;
            }

            $hasMatch = true;

            $this->offsetSet($key, $item);
        }

        if ($hasMatch || ! $setLastIfNoMatch) {
            return;
        }

        $this->add($item);
    }

    public function walk(callable $callback): void
    {
        $items = $this->data;

        array_walk(
            $items,
            $callback,
        );
    }

    /**
     * @return mixed[]
     */
    public function mapToArray(callable $callable): array
    {
        return array_map(
            $callable,
            $this->toArray(),
        );
    }
}
