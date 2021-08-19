<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing\Responders;

use App\Context\Issues\Entities\IssueCollection;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
use App\Http\Response\Support\IssueListing\Contracts\PaginatedIndexResponderContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class PaginatedIndexResponderInvalid implements PaginatedIndexResponderContract
{
    public function __construct(
        private ServerRequestInterface $request
    ) {
    }

    /**
     * @throws HttpNotFoundException
     *
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function respond(
        array $supportMenu,
        IssueCollection $issues,
        Pagination $pagination,
        Meta $meta,
    ): ResponseInterface {
        throw new HttpNotFoundException($this->request);
    }
}
