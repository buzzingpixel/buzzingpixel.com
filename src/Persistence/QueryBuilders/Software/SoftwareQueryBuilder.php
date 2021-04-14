<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Software;

use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

class SoftwareQueryBuilder extends QueryBuilder
{
    /**
     * @return class-string
     */
    public function getRecordClass(): string
    {
        return SoftwareRecord::class;
    }

    public function getRecordAlias(): string
    {
        return 's';
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
    public function withSlug(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'slug',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withName(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'name',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withIsForSale(
        bool $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'isForSale',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withPrice(
        int | float $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'price',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withRenewalPrice(
        int | float $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'renewalPrice',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withIsSubscription(
        bool $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'isSubscription',
            $value,
            $comparison,
            $concat,
        );
    }
}
