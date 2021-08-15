<?php

declare(strict_types=1);

namespace App\Context\Cache\Entities;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired
// phpcs:disable Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
// phpcs:disable SlevomatCodingStandard.Namespaces.UnusedUses.UnusedUse

use App\Utilities\DateTimeUtility;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Psr\Cache\CacheItemInterface;

use function is_int;
use function time;

class CacheItem implements CacheItemInterface
{
    private ?DateTimeImmutable $expiresAt;

    public function __construct(
        private string $key,
        private mixed $value = null,
        null | string | DateTimeInterface $expiresAt = null,
        private bool $isHit = false,
    ) {
        $this->expiresAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $expiresAt
        );
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function expiresAfter(DateInterval | int | null $time): static
    {
        if ($time === null) {
            $this->expiresAt = null;

            return $this;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $expires = (new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC'),
        ));

        if ($time instanceof DateInterval) {
            $expires = $expires->add($time);

            $this->expiresAt = $expires;

            return $this;
        }

        $expires = $expires->setTimestamp(time() + $time);

        $this->expiresAt = $expires;

        return $this;
    }

    public function get(): mixed
    {
        return $this->value;
    }

    public function isHit(): bool
    {
        return $this->isHit;
    }

    public function set(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function expiresAt(?DateTimeInterface $expiration): static
    {
        $this->expiresAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $expiration,
        );

        return $this;
    }

    public function expires(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }
}
