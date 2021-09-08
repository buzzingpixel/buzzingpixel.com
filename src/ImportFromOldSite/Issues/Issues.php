<?php

declare(strict_types=1);

namespace App\ImportFromOldSite\Issues;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueMessage;
use App\Context\Issues\Entities\IssueMessageCollection;
use App\Context\Issues\Entities\IssueSubscriber;
use App\Context\Issues\Entities\IssueSubscriberCollection;
use App\Context\Issues\IssuesApi;
use App\Context\Software\SoftwareApi;
use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Issues\IssueQueryBuilder;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use DateTimeImmutable;
use DateTimeInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

use function array_map;
use function assert;
use function json_decode;
use function var_export;

class Issues
{
    public function __construct(
        private Client $guzzle,
        private General $config,
        private UserApi $userApi,
        private IssuesApi $issuesApi,
        private SoftwareApi $softwareApi,
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function import(): void
    {
        $request = $this->guzzle->get(
            $this->config->oldSiteUrl(
                uri: '/new-site-transfer/issues'
            ),
            [
                'query' => ['key' => $this->config->oldSiteTransferKey()],
            ],
        );

        // dd(count(json_decode((string) $request->getBody(), true)));

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedArrayAccess
         */
        array_map(
            [$this, 'importItem'],
            json_decode((string) $request->getBody(), true)
        );
    }

    /**
     * @param mixed[] $item
     */
    protected function importItem(array $item): void
    {
        $alreadyImportedIssue = $this->issuesApi->fetchOneIssue(
            queryBuilder: (new IssueQueryBuilder())
                ->withIssueNumber(value: (int) $item['issueNumber'])
        );

        if ($alreadyImportedIssue !== null) {
            return;
        }

        $user = $this->userApi->fetchOneUser(
            queryBuilder: (new UserQueryBuilder())
                ->withEmailAddress((string) $item['userEmail']),
        );

        assert($user instanceof User);

        $software = $this->softwareApi->fetchOneSoftware(
            queryBuilder: (new SoftwareQueryBuilder())
                ->withSlug((string) $item['softwareSlug']),
        );

        $createdAt = DateTimeImmutable::createFromFormat(
            DateTimeInterface::ATOM,
            (string) $item['createdAt'],
        );

        assert($createdAt instanceof DateTimeImmutable);

        $updatedAt = DateTimeImmutable::createFromFormat(
            DateTimeInterface::ATOM,
            (string) $item['updatedAt'],
        );

        assert($updatedAt instanceof DateTimeImmutable);

        /** @var string[] $issueMessageArray */
        $issueMessageArray = (array) $item['issueMessages'];

        /** @var array<string, bool> $issueSubscribersArray */
        $issueSubscribersArray = (array) $item['issueSubscribers'];

        $issue = new Issue(
            shortDescription: (string) $item['shortDescription'],
            issueNumber: (int) $item['issueNumber'],
            status: (string) $item['status'],
            isPublic: (bool) $item['isPublic'],
            softwareVersion: (string) $item['softwareVersion'],
            cmsVersion: (string) $item['cmsVersion'],
            phpVersion: (string) $item['phpVersion'],
            mySqlVersion: (string) $item['mySqlVersion'],
            additionalEnvDetails: (string) $item['additionalEnvDetails'],
            privateInfo: (string) $item['privateInfo'],
            solution: (string) $item['solution'],
            legacySolutionFile: (string) $item['legacySolutionFile'],
            user: $user,
            software: $software,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );

        /**
         * @psalm-suppress InvalidArgument
         */
        $issue = $issue->withIssueMessages(
            issueMessages: (new IssueMessageCollection(array_map(
                function (array $messageItem): IssueMessage {
                    $user = $this->userApi->fetchOneUser(
                        queryBuilder: (new UserQueryBuilder())
                            ->withEmailAddress((string) $messageItem['userEmail']),
                    );

                    assert($user instanceof User);

                    $createdAt = DateTimeImmutable::createFromFormat(
                        DateTimeInterface::ATOM,
                        (string) $messageItem['createdAt'],
                    );

                    assert($createdAt instanceof DateTimeImmutable);

                    $updatedAt = DateTimeImmutable::createFromFormat(
                        DateTimeInterface::ATOM,
                        (string) $messageItem['updatedAt'],
                    );

                    assert($updatedAt instanceof DateTimeImmutable);

                    return new IssueMessage(
                        message: (string) $messageItem['message'],
                        user: $user,
                        createdAt: $createdAt,
                        updatedAt: $updatedAt,
                    );
                },
                $issueMessageArray,
            )))->sort(
                'createdAtTimeStamp',
                'asc',
            ),
        );

        $issueSubscribers = new IssueSubscriberCollection();

        foreach ($issueSubscribersArray as $emailAddress => $isActive) {
            $user = $this->userApi->fetchOneUser(
                queryBuilder: (new UserQueryBuilder())
                    ->withEmailAddress($emailAddress),
            );

            $issueSubscribers->add(new IssueSubscriber(
                user: $user,
                isActive: $isActive,
                issue: $issue,
            ));
        }

        $issue = $issue->withIssueSubscribers(
            issueSubscribers: $issueSubscribers
        );

        $payload = $this->issuesApi->saveIssue(
            issue: $issue,
            setUpdatedAt: false,
            setNewIssueNumber: false,
            sendNotifications: false,
        );

        if ($payload->getStatus() === Payload::STATUS_CREATED) {
            return;
        }

        var_export($payload);
        die;
    }
}
