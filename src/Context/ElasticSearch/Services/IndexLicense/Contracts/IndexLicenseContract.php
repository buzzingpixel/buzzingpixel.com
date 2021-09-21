<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexLicense\Contracts;

use App\Context\Licenses\Entities\License;

interface IndexLicenseContract
{
    public function indexLicense(License $license): void;
}
