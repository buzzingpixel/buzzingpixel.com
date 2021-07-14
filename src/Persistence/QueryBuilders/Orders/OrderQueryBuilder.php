<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Orders;

use App\Persistence\Entities\Orders\OrderRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

class OrderQueryBuilder extends QueryBuilder
{
    public function getRecordClass(): string
    {
        return OrderRecord::class;
    }

    public function getRecordAlias(): string
    {
        return 'o';
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
    public function withStripeId(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'stripeId',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withUserId(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'user',
            $value,
            $comparison,
            $concat,
        );
    }
}
