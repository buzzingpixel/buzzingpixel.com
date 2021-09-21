<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\PaginatedIndex;

use Psr\Http\Message\ResponseInterface;

interface PaginatedUsersResponderContract
{
    public function respond(): ResponseInterface;
}
