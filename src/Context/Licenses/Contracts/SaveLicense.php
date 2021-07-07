<?php

declare(strict_types=1);

namespace App\Context\Licenses\Contracts;

use App\Context\Licenses\Entities\License;
use App\Payload\Payload;

interface SaveLicense
{
    public function save(License $license): Payload;
}
