<?php

declare(strict_types=1);

namespace App\Context\Schedule\Entities;

use App\EntityValueObjects\Id;
use App\Persistence\Entities\Schedule\ScheduleTrackingRecord;
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

    public static function fromConfigItemAndRecord(
        ScheduleConfigItem $configItem,
        ?ScheduleTrackingRecord $record = null,
    ): self {
        return new self(
            className: $configItem->className(),
            runEvery: $configItem->runEvery(),
            isRunning: $record !== null ? $record->getIsRunning() : false,
            lastRunStartAt: $record !== null ? $record->getLastRunStartAt() : null,
            lastRunEndAt:  $record !== null ? $record->getLastRunEndAt() : null,
            id: $record !== null ? $record->getId() : null,
        );
    }

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

    public function withIsRunning(bool $isRunning): self
    {
        $clone = clone $this;

        $clone->isRunning = $isRunning;

        return $clone;
    }

    public function lastRunStartAt(): ?DateTimeImmutable
    {
        return $this->lastRunStartAt;
    }

    public function withLastRunStartAt(?DateTimeImmutable $lastRunStartAt): self
    {
        $clone = clone $this;

        $clone->lastRunStartAt = $lastRunStartAt;

        return $clone;
    }

    public function lastRunEndAt(): ?DateTimeImmutable
    {
        return $this->lastRunEndAt;
    }

    public function withLastRunEndAt(?DateTimeImmutable $lastRunEndAt): self
    {
        $clone = clone $this;

        $clone->lastRunEndAt = $lastRunEndAt;

        return $clone;
    }
}
