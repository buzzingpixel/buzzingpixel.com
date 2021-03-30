<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\Software;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;

class FetchOneSoftware
{
    public function __construct(private FetchSoftware $fetchSoftware)
    {
    }

    public function fetch(SoftwareQueryBuilder $queryBuilder): ?Software
    {
        $softwareCollection = $this->fetchSoftware->fetch(
            $queryBuilder->withLimit(1),
        );

        if ($softwareCollection->count() < 1) {
            return null;
        }

        return $softwareCollection->first();
    }
}
