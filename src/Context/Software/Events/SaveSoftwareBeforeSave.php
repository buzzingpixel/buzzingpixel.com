<?php

declare(strict_types=1);

namespace App\Context\Software\Events;

use App\Context\Software\Entities\Software;

class SaveSoftwareBeforeSave
{
    public function __construct(public Software $software)
    {
    }
}
