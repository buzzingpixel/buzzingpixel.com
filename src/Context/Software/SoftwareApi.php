<?php

declare(strict_types=1);

namespace App\Context\Software;

use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareCollection;
use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\Entities\SoftwareVersionCollection;
use App\Context\Software\Services\FetchOneSoftware;
use App\Context\Software\Services\FetchOneSoftwareVersion;
use App\Context\Software\Services\FetchSoftware;
use App\Context\Software\Services\FetchSoftwareVersions;
use App\Context\Software\Services\SaveSoftware;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use App\Persistence\QueryBuilders\Software\SoftwareVersionQueryBuilder;

class SoftwareApi
{
    public function __construct(
        private SaveSoftware $saveSoftware,
        private FetchSoftware $fetchSoftware,
        private FetchOneSoftware $fetchOneSoftware,
        private FetchSoftwareVersions $fetchSoftwareVersions,
        private FetchOneSoftwareVersion $fetchOneSoftwareVersion,
    ) {
    }

    public function saveSoftware(Software $software): Payload
    {
        return $this->saveSoftware->save($software);
    }

    /** @phpstan-ignore-next-line  */
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

    /** @phpstan-ignore-next-line  */
    public function fetchSoftwareVersions(
        SoftwareVersionQueryBuilder $queryBuilder,
    ): SoftwareVersionCollection {
        return $this->fetchSoftwareVersions->fetch($queryBuilder);
    }

    public function fetchOneSoftwareVersion(
        SoftwareVersionQueryBuilder $queryBuilder,
    ): ?SoftwareVersion {
        return $this->fetchOneSoftwareVersion->fetch(
            $queryBuilder
        );
    }
}
