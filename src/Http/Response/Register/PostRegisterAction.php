<?php

declare(strict_types=1);

namespace App\Http\Response\Register;

use App\Context\Users\Entities\User;
use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\Context\Users\Exceptions\InvalidPassword;
use App\Context\Users\UserApi;
use App\Payload\Payload;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostRegisterAction
{
    public function __construct(
        private PostRegisterResponder $responder,
        private UserApi $userApi,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $email = (string) ($postData['email'] ?? '');

        $password = (string) ($postData['password'] ?? '');

        $passwordVerify = (string) ($postData['password_verify']);

        if ($password !== $passwordVerify) {
            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_ERROR,
                    ['message' => 'Password verification must match'],
                ),
                '/account/register',
            );
        }

        try {
            $payload = $this->userApi->saveUser(new User(
                emailAddress: $email,
                plainTextPassword: $password,
            ));
        } catch (InvalidEmailAddress | InvalidPassword $exception) {
            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_ERROR,
                    ['message' => $exception->getMessage()],
                ),
                '/account/register',
            );
        }

        if ($payload->getStatus() !== Payload::STATUS_CREATED) {
            return $this->responder->respond(
                $payload,
                '/account/register',
            );
        }

        return $this->responder->respond(
            $payload,
            '/account',
        );
    }
}
