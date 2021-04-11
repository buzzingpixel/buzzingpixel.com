<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\Delete;

use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class DeleteUserAction
{
    public function __construct(
        private UserApi $userApi,
        private DeleteUserResponder $responder,
    ) {
    }

    /**
     * @throws HttpNotFoundException
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

        return $this->responder->respond(
            $this->userApi->deleteUser($user),
            '/admin/users',
        );
    }
}
