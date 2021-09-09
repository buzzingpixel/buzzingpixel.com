<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\LogInAs;

use App\Context\Users\UserApi;
use App\Http\Response\Admin\Users\LogInAs\Factories\LogInAsFactory;
use App\Http\Response\Admin\Users\LogInAs\Factories\ResponderFactory;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LogInAsAction
{
    public function __construct(
        private UserApi $userApi,
        private LogInAsFactory $logInAsFactory,
        private ResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $emailAddress = (string) $request->getAttribute('emailAddress');

        $user = $this->userApi->fetchOneUser(
            queryBuilder: (new UserQueryBuilder())
                ->withEmailAddress(value: $emailAddress),
        );

        $payload = $this->logInAsFactory
            ->make(user: $user)
            ->logInAs(user: $user);

        return $this->responderFactory
            ->make(payload: $payload)
            ->respond(payload: $payload);
    }
}
