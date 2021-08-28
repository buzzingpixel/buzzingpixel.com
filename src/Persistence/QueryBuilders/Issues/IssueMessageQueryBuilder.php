<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Issues;

use App\Persistence\Entities\Issues\IssueMessageRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

class IssueMessageQueryBuilder extends QueryBuilder
{
    public function getRecordClass(): string
    {
        return IssueMessageRecord::class;
    }

    public function getRecordAlias(): string
    {
        return 'im';
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
    public function withMessage(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'message',
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
