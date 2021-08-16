<?php

declare(strict_types=1);

namespace App\Http\Response\News\Detail\Responder;

use App\Context\Content\Entities\ContentItem;
use Psr\Http\Message\ResponseInterface;

interface NewsDetailResponderContract
{
    public function respond(?ContentItem $newsItem): ResponseInterface;
}
