<?php

declare(strict_types=1);

namespace App\Context\Schedule\Entities;

use App\EntityPropertyTraits\ClassName;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\IsRunning;
use App\EntityPropertyTraits\LastRunEndAt;
use App\EntityPropertyTraits\LastRunStartAt;
use App\EntityPropertyTraits\RunEvery;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Schedule\ScheduleTrackingRecord;
use DateTimeImmutable;
use LogicException;
use Ramsey\Uuid\UuidInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class ScheduleItem
{
    use Id;
    use ClassName;
    use RunEvery;
    use IsRunning;
    use LastRunStartAt;
    use LastRunEndAt;

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
            $this->id = IdValue::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = IdValue::fromString($id->toString());
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->className = $className;

        $this->runEvery = $runEvery;

        $this->isRunning = $isRunning;

        $this->lastRunStartAt = $lastRunStartAt;

        $this->lastRunEndAt = $lastRunEndAt;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;
}
