<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Users;

use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

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

    public function withUserStripeId(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'userStripeId',
            $value,
            $comparison,
            $concat,
        );
    }

    public function withIsAdmin(
        bool $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'isAdmin',
            $value,
            $comparison,
            $concat,
        );
    }

    public function withEmailAddress(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'emailAddress',
            $value,
            $comparison,
            $concat,
        );
    }

    public function withIsActive(
        bool $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'isActive',
            $value,
            $comparison,
            $concat,
        );
    }

    public function withTimezone(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'timezone',
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
}
