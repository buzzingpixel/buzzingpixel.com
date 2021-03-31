<?php

declare(strict_types=1);

namespace App\Context\Software\Events;

use App\Context\Software\Entities\Software;
use App\Events\StoppableEvent;

class DeleteSoftwareAfterDelete extends StoppableEvent
{
    public function __construct(public Software $software)
    {
    }
}
