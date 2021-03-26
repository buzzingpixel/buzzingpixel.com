<?php

declare(strict_types=1);

namespace App\Http\Response\Account\ChangePassword;

use App\Context\Users\Entities\LoggedInUser;
use App\Context\Users\Exceptions\InvalidPassword;
use App\Context\Users\UserApi;
use App\Payload\Payload;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostChangePasswordAction
{
    public function __construct(
        private PostChangePasswordResponder $responder,
        private UserApi $userApi,
        private LoggedInUser $loggedInUser,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $currentPassword = (string) ($postData['current_password'] ?? '');

        if ($currentPassword === '') {
            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_ERROR,
                    ['message' => 'Your current password is required'],
                ),
                '/account/change-password',
            );
        }

        $user = $this->userApi->validateUserPassword(
            $this->loggedInUser->user(),
            $currentPassword,
        );

        if ($user === null) {
            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_ERROR,
                    ['message' => 'Your current password was incorrect'],
                ),
                '/account/change-password',
            );
        }

        $password = (string) ($postData['password'] ?? '');

        $passwordVerify = (string) ($postData['password_verify']);

        if ($password !== $passwordVerify) {
            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_ERROR,
                    ['message' => 'Password verification must match'],
                ),
                '/account/change-password',
            );
        }

        try {
            $payload = $this->userApi->saveUser($user->withPassword(
                $password,
            ));
        } catch (InvalidPassword $exception) {
            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_ERROR,
                    ['message' => $exception->getMessage()],
                ),
                '/account/change-password',
            );
        }

        return $this->responder->respond(
            $payload,
            '/account/change-password',
        );
    }
}
