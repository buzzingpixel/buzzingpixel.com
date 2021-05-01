<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Cart;

use App\Persistence\Entities\Cart\CartRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

class CartQueryBuilder extends QueryBuilder
{
    public function getRecordClass(): string
    {
        return CartRecord::class;
    }

    public function getRecordAlias(): string
    {
        return 'c';
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
    public function withUserId(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'user_id',
            $value,
            $comparison,
            $concat,
        );
    }
}
