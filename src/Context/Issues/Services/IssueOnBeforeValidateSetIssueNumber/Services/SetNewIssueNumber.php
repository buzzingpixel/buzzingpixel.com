<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Services;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Contracts\SetNewIssueNumberContract;
use App\Persistence\Entities\Support\IssueRecord;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class SetNewIssueNumber implements SetNewIssueNumberContract
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function setIssueNumber(Issue $issue): Issue
    {
        try {
            $issueNumber = (int) $this->entityManager
                ->getRepository(IssueRecord::class)
                ->createQueryBuilder('i')
                ->select('i.issueNumber')
                ->orderBy('i.issueNumber', 'desc')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $issueNumber = 0;
        }

        return $issue->withIssueNumber($issueNumber + 1);
    }
}
