<?php

declare(strict_types=1);

namespace App\Http\Response\News\Detail\Responder;

use App\Context\Content\Entities\ContentItem;
use Psr\Http\Message\ServerRequestInterface;

class NewsDetailResponderFactory
{
    public function __construct(private NewsDetailResponder $responder)
    {
    }

    public function getResponder(
        string $slug,
        ?ContentItem $newsItem,
        ServerRequestInterface $request,
    ): NewsDetailResponderContract {
        if ($slug === '' || $newsItem === null) {
            return new NewsDetailResponderInvalid($request);
        }

        return $this->responder;
    }
}
