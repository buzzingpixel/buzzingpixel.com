<?php

declare(strict_types=1);

namespace App\Context\RequestCache\Entities;

use DateTimeInterface;
use LogicException;
use Psr\Cache\CacheItemInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class SessionCacheItem implements CacheItemInterface
{
    public function __construct(
        private string $key,
        private mixed $value = null
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function get(): mixed
    {
        return $this->value;
    }

    public function isHit(): bool
    {
        return $this->value !== null;
    }

    // phpcs:disable Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect

    public function set(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function expiresAt(?DateTimeInterface $expiration): static
    {
        throw new LogicException('Not Implemented');
    }

    // phpcs:disable SlevomatCodingStandard.Namespaces.ReferenceUsedNamesOnly.ReferenceViaFullyQualifiedName

    /**
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function expiresAfter(int | \DateInterval | null $time): static
    {
        throw new LogicException('Not Implemented');
    }
}
