<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Dashboard\Contracts;

use App\Context\Content\Entities\ContentItemCollection;
use Psr\Http\Message\ResponseInterface;

interface DashboardResponderContract
{
    /**
     * @param array<string, array<string, string|bool>> $supportMenu
     *
     * @phpstan-ignore-next-line
     */
    public function respond(
        array $supportMenu,
        ContentItemCollection $supportArticles,
    ): ResponseInterface;
}
