<?php

declare(strict_types=1);

namespace App\Context\Software\Events;

use App\Context\Software\Entities\Software;
use Throwable;

class SaveSoftwareFailed
{
    public function __construct(
        public Software $software,
        public Throwable $exception,
    ) {
    }
}
