<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

use function implode;

abstract class QueryBuilder implements IQueryBuilder
{
    /** @var SearchField[] */
    private array $search = [];

    /**
     * @return $this
     */
    public function withSearchField(
        string $property,
        string $value,
    ): self {
        $clone = clone $this;

        $clone->search[] = new SearchField(
            $property,
            $value,
        );

        return $clone;
    }

    /** @var WhereClause[] */
    private array $whereClauses = [];

    /**
     * @return $this
     */
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

    /**
     * @return $this
     */
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

    /**
     * @return $this
     */
    public function withLimit(?int $limit): self
    {
        $clone = clone $this;

        $clone->limit = $limit;

        return $clone;
    }

    private ?int $offset = null;

    /**
     * @return $this
     */
    public function withOffset(?int $offset): self
    {
        $clone = clone $this;

        $clone->offset = $offset;

        return $clone;
    }

    public function createQueryBuilder(
        EntityManager $entityManager
    ): \Doctrine\ORM\QueryBuilder {
        $paramNum = 1;

        $a = $this->getRecordAlias();

        $queryBuilder = $entityManager
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
                $queryBuilder->andWhere($predicates);
            } else {
                $queryBuilder->orWhere($predicates);
            }

            $queryBuilder->setParameter(
                $paramKey,
                $clause->value()
            );

            $paramNum++;
        }

        foreach ($this->search as $searchField) {
            $paramKey = $searchField->property() . $paramNum;

            $queryBuilder->andWhere(
                $queryBuilder->expr()->like(
                    $a . '.' . $searchField->property(),
                    ':' . $paramKey,
                ),
            );

            $queryBuilder->setParameter(
                $paramKey,
                '%' . $searchField->value() . '%',
            );
        }

        foreach ($this->orderBySet as $orderBy) {
            $queryBuilder->addOrderBy(
                $a . '.' . $orderBy->column(),
                $orderBy->direction()
            );
        }

        $queryBuilder->setMaxResults($this->limit);

        $queryBuilder->setFirstResult($this->offset);

        return $queryBuilder;
    }

    public function createQuery(EntityManager $entityManager): Query
    {
        return $this->createQueryBuilder($entityManager)
            ->getQuery();
    }
}
