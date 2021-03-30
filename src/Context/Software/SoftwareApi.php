<?php

declare(strict_types=1);

namespace App\Context\Software;

use App\Context\Software\Entities\Software;
use App\Context\Software\Services\SaveSoftware;
use App\Payload\Payload;

class SoftwareApi
{
    public function __construct(
        private SaveSoftware $saveSoftware,
    ) {
    }

    public function saveSoftware(Software $software): Payload
    {
        return $this->saveSoftware->save($software);
    }
}
