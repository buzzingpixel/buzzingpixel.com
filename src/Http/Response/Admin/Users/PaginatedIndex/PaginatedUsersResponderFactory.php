<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\PaginatedIndex;

use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment as TwigEnvironment;

class PaginatedUsersResponderFactory
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function make(
        UserResult $userResult,
        ServerRequestInterface $request,
    ): PaginatedUsersResponderContract {
        $pageNum = $userResult->pagination()->currentPage();

        $users = $userResult->users();

        if ($pageNum > 1 && $users->count() < 1) {
            return new PaginatedUsersResponderInvalid(request: $request);
        }

        return new PaginatedUsersResponder(
            twig: $this->twig,
            config: $this->config,
            userResult: $userResult,
            responseFactory: $this->responseFactory,
        );
    }
}
