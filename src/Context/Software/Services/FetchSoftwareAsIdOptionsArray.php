<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\Software;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;

use function array_merge;

class FetchSoftwareAsIdOptionsArray
{
    public function __construct(private FetchSoftware $fetchSoftware)
    {
    }

    /**
     * @return mixed[]
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function fetch(?SoftwareQueryBuilder $queryBuilder = null): array
    {
        if ($queryBuilder === null) {
            $queryBuilder = (new SoftwareQueryBuilder())
                ->withOrderBy('name', 'asc');
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $softwareCollection = $this->fetchSoftware->fetch($queryBuilder);

        return array_merge(
            [
                [
                    'value' => '',
                    'label' => '--',
                ],
            ],
            $softwareCollection->mapToArray(
                static fn (Software $software) => [
                    'value' => $software->id(),
                    'label' => $software->name(),
                ],
            )
        );
    }
}
