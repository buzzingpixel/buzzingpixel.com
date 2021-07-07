<?php

declare(strict_types=1);

namespace App\Context\Licenses\Events;

use App\Context\Licenses\Entities\License;
use App\Events\StoppableEvent;
use Throwable;

class SaveLicenseFailed extends StoppableEvent
{
    public function __construct(
        public License $license,
        public Throwable $exception,
    ) {
    }
}
