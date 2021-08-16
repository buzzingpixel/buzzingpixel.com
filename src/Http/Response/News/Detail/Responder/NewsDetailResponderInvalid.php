<?php

declare(strict_types=1);

namespace App\Http\Response\News\Detail\Responder;

use App\Context\Content\Entities\ContentItem;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class NewsDetailResponderInvalid implements NewsDetailResponderContract
{
    public function __construct(
        private ServerRequestInterface $request
    ) {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function respond(?ContentItem $newsItem): ResponseInterface
    {
        throw new HttpNotFoundException($this->request);
    }
}
