<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Profile;

use App\Context\Issues\IssuesApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Context\Users\UserApi;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Issues\IssueMessageQueryBuilder;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

use function assert;
use function is_array;

class PostAccountProfileAction
{
    public function __construct(
        private UserApi $userApi,
        private IssuesApi $issuesApi,
        private LoggedInUser $loggedInUser,
        private PostAccountProfileResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $user = $this->loggedInUser->user();

        try {
            $timeZoneString = (string) ($postData['time_zone'] ?? '');

            $user = $user->withTimezone(
                new DateTimeZone($timeZoneString)
            );
        } catch (Throwable) {
        }

        $displayName = (string) ($postData['display_name'] ?? '');

        $userReplyCount = $this->issuesApi->fetchTotalReplies(
            queryBuilder: (new IssueMessageQueryBuilder())
                ->withUserId($user->id()),
        );

        if ($userReplyCount > 0 && $displayName === '') {
            return $this->responder->respond(
                new Payload(
                    status: Payload::STATUS_NOT_VALID,
                    result: ['message' => 'Since you have posted issues, your display name is required'],
                ),
                '/account/profile',
            );
        }

        $user = $user->withSupportProfile(
            $user->supportProfile()->withDisplayName(
                displayName: $displayName,
            ),
        );

        $user = $user->withBillingProfile(
            $user->billingProfile()
                ->withBillingName(
                    (string) ($postData['billing_name'] ?? ''),
                )
                ->withBillingCompany(
                    (string) ($postData['billing_company'] ?? ''),
                )
                ->withBillingPhone(
                    (string) ($postData['billing_phone'] ?? ''),
                )
                ->withBillingCountryRegion(
                    (string) ($postData['billing_country_region'] ?? ''),
                )
                ->withBillingAddress(
                    (string) ($postData['billing_address'] ?? ''),
                )
                ->withBillingAddressContinued(
                    (string) ($postData['billing_address_continued'] ?? ''),
                )
                ->withBillingCity(
                    (string) ($postData['billing_city'] ?? ''),
                )
                ->withBillingStateProvince(
                    (string) ($postData['billing_state_province'] ?? ''),
                )
                ->withBillingPostalCode(
                    (string) ($postData['billing_postal_code'] ?? ''),
                )
        );

        $payload = $this->userApi->saveUser($user);

        return $this->responder->respond(
            $payload,
            '/account/profile',
        );
    }
}
