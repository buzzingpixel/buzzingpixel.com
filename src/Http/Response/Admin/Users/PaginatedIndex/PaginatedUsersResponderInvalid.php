<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\PaginatedIndex;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class PaginatedUsersResponderInvalid implements PaginatedUsersResponderContract
{
    public function __construct(
        private ServerRequestInterface $request
    ) {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function respond(): ResponseInterface
    {
        throw new HttpNotFoundException($this->request);
    }
}
