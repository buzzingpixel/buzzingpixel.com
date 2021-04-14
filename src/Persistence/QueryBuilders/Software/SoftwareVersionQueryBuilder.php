<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Software;

use App\Persistence\Entities\Software\SoftwareVersionRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

class SoftwareVersionQueryBuilder extends QueryBuilder
{
    public function getRecordClass(): string
    {
        return SoftwareVersionRecord::class;
    }

    public function getRecordAlias(): string
    {
        return 'sv';
    }

    /**
     * @return $this
     */
    public function withId(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'id',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withMajorVersion(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'majorVersion',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withVersion(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'version',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withDownloadFile(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'downloadFile',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withUpgradePrice(
        int | float $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'upgradePrice',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withReleasedOn(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'releasedOn',
            $value,
            $comparison,
            $concat,
        );
    }
}
