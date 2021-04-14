<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Users;

use App\Persistence\Entities\Users\UserSessionRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

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
            'userId',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
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

    /**
     * @return $this
     */
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
