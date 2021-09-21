<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexLicense;

use App\Context\ElasticSearch\Services\IndexLicense\Factories\IndexLicenseFactory;
use App\Context\Licenses\Entities\License;

class IndexLicense
{
    public function __construct(
        private IndexLicenseFactory $indexLicenseFactory
    ) {
    }

    public function indexLicense(License $license): void
    {
        $this->indexLicenseFactory->make(license: $license)
            ->indexLicense(license: $license);
    }
}
