<?php

declare(strict_types=1);

namespace App\Context\Software\EventListeners;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\Events\SaveSoftwareBeforeSave;
use App\Context\Software\Services\SaveNewVersionFile;

class SaveSoftwareBeforeSaveSaveNewDownloadFiles
{
    public function __construct(
        private SaveNewVersionFile $saveNewVersionFile,
    ) {
    }

    public function onBeforeSave(SaveSoftwareBeforeSave $beforeSave): void
    {
        $beforeSave->software->versions()->walk(
            function (SoftwareVersion $version) use (
                $beforeSave
            ): void {
                if ($version->newFileLocation() === '') {
                    return;
                }

                $beforeSave->software->versions()->replaceWhereMatch(
                    'id',
                    $this->saveNewVersionFile->save($version),
                );
            }
        );
    }
}
