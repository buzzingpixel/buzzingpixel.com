<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing\Contracts;

use App\Context\Issues\Entities\IssueCollection;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
use Psr\Http\Message\ResponseInterface;

interface PaginatedIndexResponderContract
{
    /**
     * @param array<string, array<string, string|bool>> $supportMenu
     *
     * @phpstan-ignore-next-line
     */
    public function respond(
        array $supportMenu,
        IssueCollection $issues,
        Pagination $pagination,
        Meta $meta,
    ): ResponseInterface;
}
