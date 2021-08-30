<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SetIssueSubscribers;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueMessage;
use App\Context\Issues\Services\SetIssueSubscribers\Factories\SetSubscriberFactory;
use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserCollection;
use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;

use function array_merge;

class SetSubscribers
{
    public function __construct(
        private UserApi $userApi,
        private SetSubscriberFactory $setSubscriberFactory,
    ) {
    }

    public function set(Issue $issue): Issue
    {
        $admins = $this->userApi->fetchUsers(
            queryBuilder: (new UserQueryBuilder())
                ->withIsAdmin(),
        );

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $users = new UserCollection(array_merge(
            $issue->issueMessages()->mapToArray(
                static fn (IssueMessage $i) => $i->userGuarantee(),
            ),
            [$issue->userGuarantee()],
            $admins->toArray(),
        ));

        $users->map(fn (User $u) => $this->setSubscriberFactory
            ->get(issue: $issue, user: $u)
            ->set(issue: $issue, user: $u));

        return $issue;
    }
}
