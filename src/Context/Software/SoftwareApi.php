<?php

declare(strict_types=1);

namespace App\Context\Software;

use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareCollection;
use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\Entities\SoftwareVersionCollection;
use App\Context\Software\Services\DeleteSoftware;
use App\Context\Software\Services\DeleteSoftwareVersion;
use App\Context\Software\Services\FetchOneSoftware;
use App\Context\Software\Services\FetchOneSoftwareVersion;
use App\Context\Software\Services\FetchSoftware;
use App\Context\Software\Services\FetchSoftwareAsIdOptionsArray;
use App\Context\Software\Services\FetchSoftwareAsOptionsArray;
use App\Context\Software\Services\FetchSoftwareByStripePriceId;
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
        private DeleteSoftware $deleteSoftware,
        private FetchOneSoftware $fetchOneSoftware,
        private DeleteSoftwareVersion $deleteSoftwareVersion,
        private FetchSoftwareVersions $fetchSoftwareVersions,
        private FetchOneSoftwareVersion $fetchOneSoftwareVersion,
        private FetchSoftwareAsOptionsArray $fetchSoftwareAsOptionsArray,
        private FetchSoftwareByStripePriceId $fetchSoftwareByStripePriceId,
        private FetchSoftwareAsIdOptionsArray $fetchSoftwareAsIdOptionsArray,
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

    /**
     * @return mixed[]
     */
    public function fetchSoftwareAsOptionsArray(
        ?SoftwareQueryBuilder $queryBuilder = null
    ): array {
        return $this->fetchSoftwareAsOptionsArray->fetch(
            queryBuilder: $queryBuilder
        );
    }

    /**
     * @return mixed[]
     */
    public function fetchSoftwareAsIdOptionsArray(
        ?SoftwareQueryBuilder $queryBuilder = null
    ): array {
        return $this->fetchSoftwareAsIdOptionsArray->fetch(
            queryBuilder: $queryBuilder
        );
    }

    public function fetchOneSoftware(
        SoftwareQueryBuilder $queryBuilder,
    ): ?Software {
        return $this->fetchOneSoftware->fetch($queryBuilder);
    }

    public function fetchSoftwareByStripeId(string $stripeId): Software
    {
        return $this->fetchSoftwareByStripePriceId->fetch($stripeId);
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

    public function deleteSoftware(Software $software): Payload
    {
        return $this->deleteSoftware->delete($software);
    }

    public function deleteSoftwareVersion(
        SoftwareVersion $softwareVersion
    ): Payload {
        return $this->deleteSoftwareVersion->delete(
            $softwareVersion
        );
    }
}
