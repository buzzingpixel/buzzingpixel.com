<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Services;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueSubscriber;
use App\Context\Issues\Entities\IssueSubscriberCollection;
use App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Contracts\SetInitialSubscribersContract;
use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;

use function array_merge;

class SetInitialSubscribers implements SetInitialSubscribersContract
{
    public function __construct(private UserApi $userApi)
    {
    }

    public function set(Issue $issue): Issue
    {
        $admins = $this->userApi->fetchUsers(
            queryBuilder: (new UserQueryBuilder())
                ->withIsAdmin(),
        );

        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        return $issue->withIssueSubscribers(
            issueSubscribers: new IssueSubscriberCollection(array_merge(
                $admins->mapToArray(
                    static fn (User $user) => new IssueSubscriber(
                        user: $user,
                    ),
                ),
                [new IssueSubscriber(user: $issue->userGuarantee())],
            )),
        );
    }
}
