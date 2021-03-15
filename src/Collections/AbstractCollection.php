<?php

declare(strict_types=1);

namespace App\Collections;

use Ramsey\Collection\AbstractCollection as RamseyAbstractCollection;
use Ramsey\Collection\Exception\ValueExtractionException;

use function get_class;
use function is_object;
use function method_exists;
use function property_exists;
use function sprintf;

// phpcs:disable SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming.SuperfluousPrefix
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint

/** @phpstan-ignore-next-line  */
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
     *
     * @noinspection PhpMissingParamTypeInspection
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
     * @psalm-suppress MissingReturnType
     * @phpstan-ignore-next-line
     */
    public function firstOrNull()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->first();
    }
}
