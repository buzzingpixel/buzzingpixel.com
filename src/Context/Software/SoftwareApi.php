<?php

declare(strict_types=1);

namespace App\Context\Software;

use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareCollection;
use App\Context\Software\Services\FetchOneSoftware;
use App\Context\Software\Services\FetchSoftware;
use App\Context\Software\Services\SaveSoftware;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;

class SoftwareApi
{
    public function __construct(
        private SaveSoftware $saveSoftware,
        private FetchSoftware $fetchSoftware,
        private FetchOneSoftware $fetchOneSoftware,
    ) {
    }

    public function saveSoftware(Software $software): Payload
    {
        return $this->saveSoftware->save($software);
    }

    public function fetchSoftware(
        SoftwareQueryBuilder $queryBuilder,
    ): SoftwareCollection {
        return $this->fetchSoftware->fetch($queryBuilder);
    }

    public function fetchOneSoftware(
        SoftwareQueryBuilder $queryBuilder,
    ): ?Software {
        return $this->fetchOneSoftware->fetch($queryBuilder);
    }
}
