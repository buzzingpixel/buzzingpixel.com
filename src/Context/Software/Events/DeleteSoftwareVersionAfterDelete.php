<?php

declare(strict_types=1);

namespace App\Context\Software\Events;

use App\Context\Software\Entities\SoftwareVersion;
use App\Events\StoppableEvent;

class DeleteSoftwareVersionAfterDelete extends StoppableEvent
{
    public function __construct(public SoftwareVersion $softwareVersion)
    {
    }
}
