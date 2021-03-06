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

    public function withWhere(
        string $property,
        string $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self;

    public function withOrderBy(string $column, string $direction = 'ASC'): self;

    public function withLimit(?int $limit): self;

    public function withOffset(?int $offset): self;

    public function createQuery(EntityManager $entityManager): Query;
}
