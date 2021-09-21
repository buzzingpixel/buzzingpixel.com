<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexUsers;

use App\Context\ElasticSearch\Services\IndexUser\IndexUser;
use App\Context\ElasticSearch\Services\IndexUsers\Services\DeleteIndexedUsersNotPresentInUsers;
use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Elasticsearch\Client;

use function array_map;

class IndexUsers
{
    public function __construct(
        private Client $client,
        private UserApi $userApi,
        private IndexUser $indexUser,
        private DeleteIndexedUsersNotPresentInUsers $deleteUsersNotPresent,
    ) {
    }

    public function indexUsers(): void
    {
        $users = $this->userApi->fetchUsers(
            queryBuilder: new UserQueryBuilder(),
        );

        $userIds = $users->mapToArray(static fn (User $u) => $u->id());

        $index = $this->client->search([
            'index' => 'users',
            'body' => ['size' => 10000],
        ]);

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedArrayAccess
         * @psalm-suppress MissingClosureReturnType
         */
        $indexedIds = array_map(
            static fn (array $i) => $i['_id'],
            $index['hits']['hits'],
        );

        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        $this->deleteUsersNotPresent->run(
            userIds: $userIds,
            indexedIds: $indexedIds,
        );

        $users->map(function (User $user): void {
            $this->indexUser->indexUser(user: $user);
        });
    }
}
