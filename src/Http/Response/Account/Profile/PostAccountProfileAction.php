<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Profile;

use App\Context\Users\Entities\LoggedInUser;
use App\Context\Users\UserApi;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

use function assert;
use function is_array;

class PostAccountProfileAction
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private UserApi $userApi,
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

        $user = $user->withSupportProfile(
            $user->supportProfile()->withDisplayName(
                (string) ($postData['display_name'] ?? ''),
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
