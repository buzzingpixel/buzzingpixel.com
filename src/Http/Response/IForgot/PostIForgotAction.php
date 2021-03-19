<?php

declare(strict_types=1);

namespace App\Http\Response\IForgot;

use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostIForgotAction
{
    public function __construct(
        private PostIForgotResponder $responder,
        private UserApi $userApi,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();

        assert(is_array($post));

        $email = (string) ($post['email'] ?? '');

        $user = $this->userApi->fetchOneUser(
            (new UserQueryBuilder())
                ->withEmailAddress($email),
        );

        if ($user === null) {
            return $this->responder->respond();
        }

        if ($this->userApi->fetchTotalUserResetTokens($user) > 5) {
            return $this->responder->respond();
        }

        $this->userApi->requestPasswordResetEmail($user);

        return $this->responder->respond();
    }
}
