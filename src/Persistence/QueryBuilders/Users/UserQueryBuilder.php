<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Users;

use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

/**
 * @psalm-suppress MoreSpecificImplementedParamType
 * @method UserQueryBuilder withWhere(string $property, mixed $value, string $comparison = '=', string $concat = 'AND')
 * @method UserQueryBuilder withOrderBy(string $column, string $direction = 'ASC')
 * @method UserQueryBuilder withLimit(?int $limit)
 * @method UserQueryBuilder withOffset(?int $offset)
 */
class UserQueryBuilder extends QueryBuilder
{
    /**
     * @return class-string
     */
    public function getRecordClass(): string
    {
        return UserRecord::class;
    }

    public function getRecordAlias(): string
    {
        return 'u';
    }
}
