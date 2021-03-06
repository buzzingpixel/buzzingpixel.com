<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

use function implode;

abstract class QueryBuilder implements IQueryBuilder
{
    /** @var WhereClause[] */
    private array $whereClauses = [];

    public function withWhere(
        string $property,
        mixed $value,
        string $comparison = '=',
        string $concat = 'AND',
    ): self {
        $clone = clone $this;

        $clone->whereClauses[] = new WhereClause(
            $property,
            $value,
            $comparison,
            $concat,
        );

        return $clone;
    }

    /** @var OrderBy[] */
    private array $orderBySet = [];

    public function withOrderBy(string $column, string $direction = 'ASC'): self
    {
        $clone = clone $this;

        $clone->orderBySet[] = new OrderBy(
            $column,
            $direction
        );

        return $clone;
    }

    private ?int $limit = null;

    public function withLimit(?int $limit): self
    {
        $clone = clone $this;

        $clone->limit = $limit;

        return $clone;
    }

    private ?int $offset = null;

    public function withOffset(?int $offset): self
    {
        $clone = clone $this;

        $clone->offset = $offset;

        return $clone;
    }

    public function createQuery(EntityManager $entityManager): Query
    {
        $paramNum = 1;

        $a = $this->getRecordAlias();

        $query = $entityManager
            ->getRepository($this->getRecordClass())
            ->createQueryBuilder($a);

        foreach ($this->whereClauses as $clause) {
            $paramKey = $clause->property() . $paramNum;

            $predicates = implode(' ', [
                $a . '.' . $clause->property(),
                $clause->comparison(),
                ':' . $paramKey,
            ]);

            if ($clause->concat() === 'AND') {
                $query->andWhere($predicates);
            } else {
                $query->orWhere($predicates);
            }

            $query->setParameter($paramKey, $clause->value());

            $paramNum++;
        }

        foreach ($this->orderBySet as $orderBy) {
            $query->addOrderBy(
                $a . '.' . $orderBy->column(),
                $orderBy->direction()
            );
        }

        $query->setMaxResults($this->limit);

        $query->setFirstResult($this->offset);

        return $query->getQuery();
    }
}
