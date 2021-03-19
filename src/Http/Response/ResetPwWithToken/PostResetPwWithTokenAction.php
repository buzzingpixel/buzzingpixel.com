<?php

declare(strict_types=1);

namespace App\Http\Response\ResetPwWithToken;

use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\Context\Users\Exceptions\InvalidPassword;
use App\Context\Users\UserApi;
use App\Payload\Payload;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

use function assert;
use function is_array;

class PostResetPwWithTokenAction
{
    public function __construct(
        private PostResetPwWithTokenResponder $responder,
        private UserApi $userApi,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $token = (string) $request->getAttribute('token');

        $user = $this->userApi->fetchUserByResetToken($token);

        if ($user === null) {
            throw new HttpNotFoundException($request);
        }

        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $password = (string) ($postData['password'] ?? '');

        $passwordVerify = (string) ($postData['password_verify']);

        if ($password !== $passwordVerify) {
            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_ERROR,
                    ['message' => 'Password verification must match'],
                ),
                '/reset-pw-with-token/' . $token,
            );
        }

        try {
            $payload = $this->userApi->saveUser($user->withPassword(
                $password,
            ));
        } catch (InvalidEmailAddress | InvalidPassword $exception) {
            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_ERROR,
                    ['message' => $exception->getMessage()],
                ),
                '/reset-pw-with-token/' . $token,
            );
        }

        if ($payload->getStatus() !== Payload::STATUS_UPDATED) {
            return $this->responder->respond(
                $payload,
                '/reset-pw-with-token/' . $token,
            );
        }

        return $this->responder->respond(
            $payload,
            '/account',
        );
    }
}
