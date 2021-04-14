<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

interface IQueryBuilder
{
    /**
     * @return class-string
     */
    public function getRecordClass(): string;

    public function getRecordAlias(): string;

    /**
     * @return $this
     */
    public function withSearchField(
        string $property,
        string $value,
    ): self;

    /**
     * @return $this
     */
    public function withWhere(
        string $property,
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self;

    /**
     * @return $this
     */
    public function withOrderBy(string $column, string $direction = 'ASC'): self;

    /**
     * @return $this
     */
    public function withLimit(?int $limit): self;

    /**
     * @return $this
     */
    public function withOffset(?int $offset): self;

    public function createQuery(EntityManager $entityManager): Query;
}
