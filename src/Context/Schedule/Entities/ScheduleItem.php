<?php

declare(strict_types=1);

namespace App\Context\Schedule\Entities;

use App\EntityValueObjects\Id;
use DateTimeImmutable;
use LogicException;
use Ramsey\Uuid\UuidInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class ScheduleItem
{
    private Id $id;
    private string $className;
    private float | int | string $runEvery;
    private bool $isRunning;
    private ?DateTimeImmutable $lastRunStartAt;
    private ?DateTimeImmutable $lastRunEndAt;

    public function __construct(
        string $className,
        float | int | string $runEvery,
        bool $isRunning = false,
        ?DateTimeImmutable $lastRunStartAt = null,
        ?DateTimeImmutable $lastRunEndAt = null,
        null | string | UuidInterface $id = null,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        if ($id === null) {
            $this->id = Id::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = Id::fromString($id->toString());
        } else {
            $this->id = Id::fromString($id);
        }

        $this->className = $className;

        $this->runEvery = $runEvery;

        $this->isRunning = $isRunning;

        $this->lastRunStartAt = $lastRunStartAt;

        $this->lastRunEndAt = $lastRunEndAt;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function id(): string
    {
        return $this->id->toString();
    }

    public function className(): string
    {
        return $this->className;
    }

    public function runEvery(): float | int | string
    {
        return $this->runEvery;
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    public function lastRunStartAt(): ?DateTimeImmutable
    {
        return $this->lastRunStartAt;
    }

    public function lastRunEndAt(): ?DateTimeImmutable
    {
        return $this->lastRunEndAt;
    }
}
