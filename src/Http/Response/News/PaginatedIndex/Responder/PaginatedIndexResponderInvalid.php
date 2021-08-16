<?php

declare(strict_types=1);

namespace App\Http\Response\News\PaginatedIndex\Responder;

use App\Context\Content\Entities\ContentItemCollection;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
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
     * @phpstan-ignore-next-line
     */
    public function respond(
        ContentItemCollection $newsCollection,
        Pagination $pagination,
        Meta $meta,
    ): ResponseInterface {
        throw new HttpNotFoundException($this->request);
    }
}
