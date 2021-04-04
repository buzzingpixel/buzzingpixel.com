<?php

declare(strict_types=1);

namespace App\Context\Software;

use App\Context\Software\EventListeners\SaveSoftwareBeforeSaveSaveNewDownloadFiles;
use Crell\Tukio\OrderedListenerProvider;

class SoftwareRegisterEventListeners
{
    public static function register(OrderedListenerProvider $provider): void
    {
        $provider->addSubscriber(
            SaveSoftwareBeforeSaveSaveNewDownloadFiles::class,
            SaveSoftwareBeforeSaveSaveNewDownloadFiles::class,
        );
    }
}
