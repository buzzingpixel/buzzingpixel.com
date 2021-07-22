<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\LicenseQueryBuilder;

use App\Persistence\Entities\Licenses\LicenseRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

class LicenseQueryBuilder extends QueryBuilder
{
    public function getRecordClass(): string
    {
        return LicenseRecord::class;
    }

    public function getRecordAlias(): string
    {
        return 'l';
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
    public function withIsDisabled(
        bool $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'isDisabled',
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
    public function withLicenseKey(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'licenseKey',
            $value,
            $comparison,
            $concat,
        );
    }

    public function withExpiresAt(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'expiresAt',
            $value,
            $comparison,
            $concat,
        );
    }
}
