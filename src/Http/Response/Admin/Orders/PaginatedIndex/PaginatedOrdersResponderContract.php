<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use Psr\Http\Message\ResponseInterface;

interface PaginatedOrdersResponderContract
{
    public function respond(): ResponseInterface;
}
