<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\SoftwareVersion;
use App\Persistence\QueryBuilders\Software\SoftwareVersionQueryBuilder;

class FetchOneSoftwareVersion
{
    public function __construct(
        private FetchSoftwareVersions $fetchSoftwareVersions
    ) {
    }

    public function fetch(
        SoftwareVersionQueryBuilder $queryBuilder
    ): ?SoftwareVersion {
        $collection = $this->fetchSoftwareVersions->fetch(
            $queryBuilder->withLimit(1),
        );

        if ($collection->count() < 1) {
            return null;
        }

        return $collection->first();
    }
}
