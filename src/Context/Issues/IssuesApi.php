<?php

declare(strict_types=1);

namespace App\Context\Issues;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Issues\Services\FetchIssues;
use App\Context\Issues\Services\FetchOneIssue;
use App\Context\Issues\Services\FetchPublicIssues;
use App\Context\Issues\Services\FetchPublicPlusUsersPrivateIssues;
use App\Context\Issues\Services\FetchTotalIssues;
use App\Context\Issues\Services\FetchUsersIssues;
use App\Context\Issues\Services\SaveIssue;
use App\Context\Issues\Services\SearchIssues\SearchPublicIssues;
use App\Context\Issues\Services\SearchIssues\SearchPublicPlusUsersIssues;
use App\Context\Issues\Services\SearchIssues\SearchUserIssues;
use App\Context\Users\Entities\User;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Support\IssueQueryBuilder;

class IssuesApi
{
    public function __construct(
        private SaveIssue $saveIssue,
        private FetchIssues $fetchIssues,
        private FetchOneIssue $fetchOneIssue,
        private FetchTotalIssues $fetchTotalIssues,
        private FetchUsersIssues $fetchUsersIssues,
        private SearchUserIssues $searchUserIssues,
        private FetchPublicIssues $fetchPublicIssues,
        private SearchPublicIssues $searchPublicIssues,
        private SearchPublicPlusUsersIssues $searchPublicPlusUsersIssues,
        private FetchPublicPlusUsersPrivateIssues $fetchPublicPlusUsersPrivateIssues,
    ) {
    }

    public function saveIssue(Issue $issue): Payload
    {
        return $this->saveIssue->save(issue: $issue);
    }

    /** @phpstan-ignore-next-line */
    public function fetchIssues(
        IssueQueryBuilder $queryBuilder
    ): IssueCollection {
        return $this->fetchIssues->fetch(queryBuilder: $queryBuilder);
    }

    public function fetchOneIssue(IssueQueryBuilder $queryBuilder): ?Issue
    {
        return $this->fetchOneIssue->fetch(queryBuilder: $queryBuilder);
    }

    public function fetchTotalIssues(
        ?IssueQueryBuilder $queryBuilder = null
    ): int {
        return $this->fetchTotalIssues->fetch(queryBuilder: $queryBuilder);
    }

    public function fetchPublicPlusUsersPrivateIssues(
        User $user,
        ?FetchParams $fetchParams = null,
    ): IssuesResult {
        return $this->fetchPublicPlusUsersPrivateIssues->fetch(
            user: $user,
            fetchParams: $fetchParams
        );
    }

    public function fetchPublicIssues(
        ?FetchParams $fetchParams = null
    ): IssuesResult {
        return $this->fetchPublicIssues->fetch(fetchParams: $fetchParams);
    }

    public function fetchUsersIssues(
        User $user,
        ?FetchParams $fetchParams = null,
    ): IssuesResult {
        return $this->fetchUsersIssues->fetch(
            user: $user,
            fetchParams: $fetchParams,
        );
    }

    public function searchUserIssues(
        string $searchString,
        User $user,
        ?FetchParams $fetchParams = null,
    ): IssuesResult {
        return $this->searchUserIssues->search(
            searchString: $searchString,
            user: $user,
            fetchParams: $fetchParams,
        );
    }

    public function searchPublicIssues(
        string $searchString,
        ?FetchParams $fetchParams = null,
    ): IssuesResult {
        return $this->searchPublicIssues->search(
            searchString: $searchString,
            fetchParams: $fetchParams,
        );
    }

    public function searchPublicPlusUsersIssues(
        string $searchString,
        User $user,
        ?FetchParams $fetchParams = null,
    ): IssuesResult {
        return $this->searchPublicPlusUsersIssues->search(
            searchString: $searchString,
            user: $user,
            fetchParams: $fetchParams,
        );
    }
}
