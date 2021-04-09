<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\Create;

use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserBillingProfile;
use App\Context\Users\Entities\UserSupportProfile;
use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\Context\Users\Exceptions\InvalidPassword;
use App\Context\Users\UserApi;
use App\Factories\ValidationFactory;
use App\Payload\Payload;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as V;
use Throwable;

use function assert;
use function is_array;

class PostCreateUserAction
{
    public function __construct(
        private UserApi $userApi,
        private ValidationFactory $validationFactory,
        private PostCreateUserResponder $responder,
    ) {
    }

    /**
     * @throws InvalidPassword
     * @throws InvalidEmailAddress
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $redirect = '/admin/users/create';

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

        if ($data['password'] !== $data['password_verify']) {
            $passwordMessage = 'Password verification must match';
        }

        $validator = $this->validationFactory->make();

        /** @psalm-suppress MixedArgument */
        $validator->validate(
            $data,
            [
                'email_address' => V::allOf(
                    V::email(),
                )->setTemplate('Email address must be valid'),
                'password' => V::allOf(
                    V::notEmpty()->setTemplate('Password must not be empty'),
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

        $user = new User(
            isAdmin: $data['is_admin'],
            emailAddress: $data['email_address'],
            plainTextPassword: $data['password'],
            isActive: $data['is_active'],
            timezone: $data['time_zone'],
        );

        $user = $user->withSupportProfile(new UserSupportProfile(
            displayName: $data['display_name'],
        ));

        $user = $user->withBillingProfile(new UserBillingProfile(
            billingName: $data['billing_name'],
            billingCompany: $data['billing_company'],
            billingPhone: $data['billing_phone'],
            billingCountryRegion: $data['billing_country_region'],
            billingAddress: $data['billing_address'],
            billingAddressContinued: $data['billing_address_continued'],
            billingCity: $data['billing_city'],
            billingStateProvince: $data['billing_state_province'],
            billingPostalCode: $data['billing_postal_code'],
        ));

        $payload = $this->userApi->saveUser($user);

        if ($payload->getStatus() === Payload::STATUS_CREATED) {
            $redirect = '/admin/users';
        }

        return $this->responder->respond(
            $payload,
            $redirect,
            $postData,
        );
    }
}
