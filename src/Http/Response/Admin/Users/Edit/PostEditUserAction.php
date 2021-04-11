<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\Edit;

use App\Context\Users\Entities\User;
use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\Context\Users\Exceptions\InvalidPassword;
use App\Context\Users\UserApi;
use App\Factories\ValidationFactory;
use App\Http\Response\Admin\Users\Create\PostCreateUserResponder;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as V;
use Slim\Exception\HttpNotFoundException;
use Throwable;

use function assert;
use function is_array;

class PostEditUserAction
{
    public function __construct(
        private UserApi $userApi,
        private ValidationFactory $validationFactory,
        private PostCreateUserResponder $responder,
    ) {
    }

    /**
     * @throws HttpNotFoundException
     * @throws InvalidEmailAddress
     * @throws InvalidPassword
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $emailAddress = (string) $request->getAttribute('emailAddress');

        $user = $this->userApi->fetchOneUser(
            (new UserQueryBuilder())
                ->withEmailAddress($emailAddress),
        );

        if ($user === null) {
            throw new HttpNotFoundException($request);
        }

        $redirect = '/admin/users/' . $user->emailAddress() . '/edit';

        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $data = [
            'is_admin' => (bool) ($postData['is_admin'] ?? '0'),
            'email_address' => (string) ($postData['email_address'] ?? ''),
            'password' => (string) ($postData['password'] ?? ''),
            'password_verify' => (string) ($postData['password_verify'] ?? ''),
            'is_active' => (bool) ($postData['is_active'] ?? '1'),
            'time_zone' => (string) ($postData['time_zone'] ?? ''),
            'display_name' => (string) ($postData['display_name'] ?? ''),
            'billing_name' => (string) ($postData['billing_name'] ?? ''),
            'billing_company' => (string) ($postData['billing_company'] ?? ''),
            'billing_phone' => (string) ($postData['billing_phone'] ?? ''),
            'billing_country_region' => (string) ($postData['billing_country_region'] ?? ''),
            'billing_address' => (string) ($postData['billing_address'] ?? ''),
            'billing_address_continued' => (string) ($postData['billing_address_continued'] ?? ''),
            'billing_city' => (string) ($postData['billing_city'] ?? ''),
            'billing_state_province' => (string) ($postData['billing_state_province'] ?? ''),
            'billing_postal_code' => (string) ($postData['billing_postal_code'] ?? ''),
        ];

        $validator = $this->validationFactory->make();

        /** @psalm-suppress MixedArgument */
        $validator->validate(
            $data,
            [
                'email_address' => V::allOf(
                    V::email(),
                )->setTemplate('Email address must be valid'),
                'password' => V::allOf(
                    V::callback(
                    /** @param mixed $input */
                        static function ($input) use ($data): bool {
                            return $input === $data['password_verify'];
                        }
                    )->setTemplate('Password verification must match'),
                    V::callback(
                        /** @param mixed $input */
                        static function ($input): bool {
                            try {
                                new User(
                                    emailAddress: 'test@test.test',
                                    plainTextPassword: $input,
                                );

                                return true;
                            } catch (Throwable) {
                                return false;
                            }
                        }
                    )->setTemplate((new InvalidPassword())->getMessage())
                ),
                'time_zone' => V::callback(
                /** @param mixed $input */
                    static function ($input): bool {
                        try {
                            new DateTimeZone($input);

                            return true;
                        } catch (Throwable) {
                            return false;
                        }
                    }
                )->setTemplate('Timezone must be valid'),
            ],
        );

        if (! $validator->isValid()) {
            $errors = $validator->getErrors();

            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_NOT_VALID,
                    [
                        'message' => 'The data provided was invalid',
                        'messageList' => $errors,
                    ]
                ),
                $redirect,
                $postData,
            );
        }

        if ($data['password'] !== '') {
            $user = $user->withPassword($data['password']);
        }

        $user = $user->withIsAdmin($data['is_admin'])
            ->withEmailAddress($data['email_address'])
            ->withIsActive($data['is_active'])
            ->withTimezone($data['time_zone'])
            ->withSupportProfile(
                $user->supportProfile()
                    ->withDisplayName($data['display_name']),
            )
            ->withBillingProfile(
                $user->billingProfile()
                    ->withBillingName($data['billing_name'])
                    ->withBillingCompany(
                        $data['billing_company']
                    )
                    ->withBillingPhone($data['billing_phone'])
                    ->withBillingCountryRegion(
                        $data['billing_country_region']
                    )
                    ->withBillingAddress(
                        $data['billing_address']
                    )
                    ->withBillingAddressContinued(
                        $data['billing_address_continued']
                    )
                    ->withBillingCity($data['billing_city'])
                    ->withBillingStateProvince(
                        $data['billing_state_province']
                    )
                    ->withBillingPostalCode(
                        $data['billing_postal_code']
                    ),
            );

        $payload = $this->userApi->saveUser($user);

        if ($payload->getStatus() === Payload::STATUS_UPDATED) {
            $redirect = '/admin/users/' . $user->emailAddress();
        }

        return $this->responder->respond(
            $payload,
            $redirect,
            $postData,
        );
    }
}
