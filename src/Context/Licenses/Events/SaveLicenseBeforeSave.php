<?php

declare(strict_types=1);

namespace App\Context\Licenses\Events;

use App\Context\Licenses\Entities\License;
use App\Events\StoppableEvent;

class SaveLicenseBeforeSave extends StoppableEvent
{
    public function __construct(public License $license)
    {
    }
}
