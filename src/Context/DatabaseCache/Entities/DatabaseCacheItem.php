<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache\Entities;

use App\EntityPropertyTraits\Id;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Cache\CachePoolItemRecord;
use App\Utilities\DateTimeUtility;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use LogicException;
use Ramsey\Uuid\UuidInterface;

use function is_int;
use function time;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired
// phpcs:disable Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect

class DatabaseCacheItem implements CacheItemInterface
{
    use Id;

    private string $key;
    private mixed $value;
    private ?DateTimeImmutable $expiresAt;

    public static function fromRecord(CachePoolItemRecord $record): self
    {
        return new self(
            id: $record->getId(),
            key: $record->getKey(),
            value: $record->getValue(),
            expiresAt: $record->getExpiresAt(),
        );
    }

    public function __construct(
        string $key,
        mixed $value = null,
        null | string | DateTimeInterface $expiresAt = null,
        null | string | UuidInterface $id = null,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        if ($id === null) {
            $this->id = IdValue::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = IdValue::fromString($id->toString());
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->key = $key;

        $this->value = $value;

        $this->expiresAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $expiresAt
        );

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function getKey(): string
    {
        return $this->key;
    }

    public function withKey(string $key): self
    {
        $clone = clone $this;

        $clone->key = $key;

        return $clone;
    }

    public function get(): mixed
    {
        return $this->value;
    }

    public function isHit(): bool
    {
        return $this->value !== null;
    }

    /** @psalm-suppress MoreSpecificReturnType */
    public function set(mixed $value): static
    {
        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress LessSpecificReturnStatement
         */
        return $this->withValue($value);
    }

    public function withValue(mixed $value): self
    {
        $clone = clone $this;

        $clone->value = $value;

        return $clone;
    }

    public function expiresAt(?DateTimeInterface $expiration): static
    {
        return $this->withExpiresAt($expiration);
    }

    public function getExpiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function withExpiresAt(?DateTimeInterface $expiration): static
    {
        $clone = clone $this;

        $clone->expiresAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $expiration
        );

        return $clone;
    }

    public function expires(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @inheritDoc
     */
    public function expiresAfter($time): static
    {
        $clone = clone $this;

        if ($time === null) {
            $clone->expiresAt = null;

            return $clone;
        }

        $expires = (new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC'),
        ));

        if ($time instanceof DateInterval) {
            $expires = $expires->add($time);

            $clone->expiresAt = $expires;

            return $clone;
        }

        /** @psalm-suppress RedundantCondition */
        if (is_int($time)) {
            $expires = $expires->setTimestamp(time() + $time);

            $clone->expiresAt = $expires;

            return $clone;
        }

        throw new InvalidArgumentException(
            '$time must be one of integer, \DateInterval, or null'
        );
    }
}
