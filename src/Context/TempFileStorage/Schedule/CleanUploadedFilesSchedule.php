<?php

declare(strict_types=1);

namespace App\Context\TempFileStorage\Schedule;

use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;
use App\Context\TempFileStorage\Services\CleanUploadedFiles;
use League\Flysystem\FilesystemException;

class CleanUploadedFilesSchedule
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::TEN_MINUTES,
        );
    }

    public function __construct(private CleanUploadedFiles $cleanUploadedFiles)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function __invoke(): void
    {
        $this->cleanUploadedFiles->clean();
    }
}
