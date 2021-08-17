<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Factories;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\SaveIssue\Contracts\ValidityContract;
use App\Context\Issues\Services\SaveIssue\Entities\ValidityErrorMessage;
use App\Context\Issues\Services\SaveIssue\Entities\ValidityErrorMessageCollection;
use App\Context\Issues\Services\SaveIssue\Validity\IssueIsInvalid;
use App\Context\Issues\Services\SaveIssue\Validity\IssueIsValid;
use App\Persistence\Entities\Support\IssueRecord;
use Doctrine\ORM\EntityManager;

use function assert;
use function implode;
use function in_array;

class IssueValidityFactory
{
    public function __construct(
        private EntityManager $entityManager,
    ) {
    }

    public function createIssueValidity(Issue $issue): ValidityContract
    {
        $errors = new ValidityErrorMessageCollection();

        if ($issue->issueNumber() < 1) {
            $errors->add(new ValidityErrorMessage(
                'issueNumber',
                'Issue number must be a positive integer',
            ));
        } else {
            /** @noinspection PhpUnhandledExceptionInspection */
            $issueWithSameNumber = $this->entityManager
                ->getRepository(IssueRecord::class)
                ->createQueryBuilder('i')
                ->where('i.issueNumber = :issueNumber')
                ->setParameter('issueNumber', $issue->issueNumber())
                ->andWhere('i.id != :id')
                ->setParameter('id', $issue->id())
                ->getQuery()
                ->getOneOrNullResult();

            assert(
                $issueWithSameNumber instanceof IssueRecord ||
                $issueWithSameNumber === null
            );

            if ($issueWithSameNumber !== null) {
                $errors->add(new ValidityErrorMessage(
                    'issueNumber',
                    'Issue number must not pre-exist in database',
                ));
            }
        }

        if (
            ! in_array(
                $issue->status(),
                Issue::ALL_STATUSES,
                true,
            )
        ) {
            $errors->add(new ValidityErrorMessage(
                'status',
                'Status must be one of: ' . implode(
                    ', ',
                    Issue::ALL_STATUSES
                ),
            ));
        }

        if ($issue->issueMessages()->count() < 1) {
            $errors->add(new ValidityErrorMessage(
                'issueMessages',
                'There must be at least one issue message',
            ));
        }

        if ($issue->user() === null) {
            $errors->add(new ValidityErrorMessage(
                'user',
                'An issue must have a user assigned to it',
            ));
        }

        $issueMessageMissing = false;

        $issueMessageUserMissing = false;

        foreach ($issue->issueMessages()->toArray() as $issueMessage) {
            if (! $issueMessageMissing && $issueMessage->message() === '') {
                $issueMessageMissing = true;

                $errors->add(new ValidityErrorMessage(
                    'issueMessages',
                    'Messages must have a message',
                ));
            }

            if ($issueMessageUserMissing || $issue->user() !== null) {
                continue;
            }

            $issueMessageUserMissing = true;

            $errors->add(new ValidityErrorMessage(
                'issueMessages',
                'A message must have a user assigned to it',
            ));
        }

        if ($errors->count() > 0) {
            return new IssueIsInvalid($errors);
        }

        return new IssueIsValid();
    }
}
