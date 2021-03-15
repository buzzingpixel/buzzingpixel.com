<?php

declare(strict_types=1);

namespace App\Context\Schedule\Entities;

class ScheduleConfigItem
{
    public function __construct(
        private string $className,
        private float | int | string $runEvery,
    ) {
    }

    public function className(): string
    {
        return $this->className;
    }

    public function runEvery(): float | int | string
    {
        return $this->runEvery;
    }
}
