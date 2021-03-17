<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache\Entities;

// phpcs:disable SlevomatCodingStandard.Classes.SuperfluousInterfaceNaming.SuperfluousSuffix

use DateTimeInterface;

interface CacheItemInterface extends \Psr\Cache\CacheItemInterface
{
    public function id(): string;

    public function withKey(string $key): self;

    public function withValue(mixed $value): self;

    public function expires(): ?DateTimeInterface;

    public function withExpiresAt(?DateTimeInterface $expiration): self;
}
