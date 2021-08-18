<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders\Support;

use App\Persistence\Entities\Support\IssueRecord;
use App\Persistence\QueryBuilders\QueryBuilder;

class IssueQueryBuilder extends QueryBuilder
{
    public function getRecordClass(): string
    {
        return IssueRecord::class;
    }

    public function getRecordAlias(): string
    {
        return 'i';
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
    public function withIssueNumber(
        int $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'issueNumber',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withStatus(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'status',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withIsPublic(
        bool $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'isPublic',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withIsEnabled(
        bool $value = true,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'isEnabled',
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

    /**
     * @return $this
     */
    public function withSoftwareId(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'software',
            $value,
            $comparison,
            $concat,
        );
    }

    /**
     * @return $this
     */
    public function withLastCommentUserType(
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        return $this->withWhere(
            'lastCommentUserType',
            $value,
            $comparison,
            $concat,
        );
    }
}
