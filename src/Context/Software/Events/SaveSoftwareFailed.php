<?php

declare(strict_types=1);

namespace App\Context\Software\Events;

use App\Context\Software\Entities\Software;
use App\Events\StoppableEvent;
use Throwable;

class SaveSoftwareFailed extends StoppableEvent
{
    public function __construct(
        public Software $software,
        public Throwable $exception,
    ) {
    }
}
