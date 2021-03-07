<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Users;

use App\Persistence\Entities\Users\UserSessionRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method UserSessionQueryBuilder withWhere(string $property, mixed $value, string $comparison = '=', string $concat = 'AND')
 * @method UserSessionQueryBuilder withOrderBy(string $column, string $direction = 'ASC')
 * @method UserSessionQueryBuilder withLimit(?int $limit)
 * @method UserSessionQueryBuilder withOffset(?int $offset)
 */
class UserSessionQueryBuilder extends QueryBuilder
{
    /**
     * @return class-string
     */
    public function getRecordClass(): string
    {
        return UserSessionRecord::class;
    }

    public function getRecordAlias(): string
    {
        return 'us';
    }

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

    public function withUserId(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'userId',
            $value,
            $comparison,
            $concat,
        );
    }

    public function withCreatedAt(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'createdAt',
            $value,
            $comparison,
            $concat,
        );
    }

    public function withLastTouchedAt(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'lastTouchedAt',
            $value,
            $comparison,
            $concat,
        );
    }
}
