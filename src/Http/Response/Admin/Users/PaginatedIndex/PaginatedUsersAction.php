<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\PaginatedIndex;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PaginatedUsersAction
{
    public function __construct(
        private UserResultFactory $userResultFactory,
        private PaginatedUsersResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string[] $queryParams */
        $queryParams = $request->getQueryParams();

        $userResult = $this->userResultFactory->make(
            queryParams: $queryParams,
        );

        return $this->responderFactory->make(
            request: $request,
            userResult: $userResult,
        )->respond();
    }
}
